<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pos_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number', 80)->unique();
            $table->foreignId('guest_stay_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->unsignedInteger('items_count')->default(0);
            $table->string('payment_status', 20)->default('unpaid');
            $table->string('status', 20)->default('completed');
            $table->timestamp('transaction_date')->useCurrent();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['transaction_date', 'status']);
            $table->index(['guest_stay_id', 'transaction_date']);
            $table->index(['customer_id', 'transaction_date']);
        });

        Schema::create('pos_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_transaction_id')->constrained()->cascadeOnDelete();
            $table->foreignId('catalog_item_id')->nullable()->constrained()->nullOnDelete();
            $table->string('item_name');
            $table->string('item_type', 30);
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2);
            $table->jsonb('metadata')->nullable();
            $table->timestamps();

            $table->index(['pos_transaction_id', 'item_type']);
            $table->index(['catalog_item_id', 'created_at']);
        });

        Schema::create('pos_transaction_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pos_transaction_id')->constrained()->cascadeOnDelete();
            $table->string('reference_id', 100)->nullable()->unique();
            $table->decimal('amount', 12, 2);
            $table->string('payment_method', 30);
            $table->string('status', 20)->default('completed');
            $table->timestamp('paid_at')->useCurrent();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();

            $table->index(['pos_transaction_id', 'status']);
        });

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE pos_transactions ADD CONSTRAINT pos_transactions_amounts_valid CHECK (subtotal >= 0 AND discount >= 0 AND tax >= 0 AND total >= 0)");
            DB::statement("ALTER TABLE pos_transactions ADD CONSTRAINT pos_transactions_payment_status_valid CHECK (payment_status IN ('unpaid', 'partial', 'paid', 'refunded'))");
            DB::statement("ALTER TABLE pos_transactions ADD CONSTRAINT pos_transactions_status_valid CHECK (status IN ('completed', 'cancelled', 'refunded'))");
            DB::statement("ALTER TABLE pos_transaction_items ADD CONSTRAINT pos_transaction_items_values_valid CHECK (quantity > 0 AND unit_price >= 0 AND discount >= 0 AND discount <= quantity * unit_price AND line_total >= 0)");
            DB::statement("ALTER TABLE pos_transaction_payments ADD CONSTRAINT pos_transaction_payments_amount_positive CHECK (amount > 0)");
            DB::statement("ALTER TABLE pos_transaction_payments ADD CONSTRAINT pos_transaction_payments_status_valid CHECK (status IN ('pending', 'completed', 'failed', 'refunded'))");

            DB::unprepared(<<<'SQL'
                CREATE OR REPLACE FUNCTION public.resort_set_pos_item_line_total()
                RETURNS trigger
                LANGUAGE plpgsql
                AS $$
                BEGIN
                    NEW.line_total := ROUND((NEW.quantity * NEW.unit_price) - NEW.discount, 2);
                    RETURN NEW;
                END;
                $$;

                CREATE TRIGGER resort_set_pos_item_line_total
                BEFORE INSERT OR UPDATE OF quantity, unit_price, discount
                ON public.pos_transaction_items
                FOR EACH ROW
                EXECUTE FUNCTION public.resort_set_pos_item_line_total();

                CREATE OR REPLACE FUNCTION public.resort_refresh_pos_transaction()
                RETURNS trigger
                LANGUAGE plpgsql
                AS $$
                DECLARE
                    target_transaction_id bigint;
                    calculated_subtotal numeric(12, 2);
                    calculated_line_total numeric(12, 2);
                    calculated_items_count integer;
                    calculated_paid numeric(12, 2);
                BEGIN
                    IF TG_OP = 'DELETE' THEN
                        target_transaction_id := OLD.pos_transaction_id;
                    ELSE
                        target_transaction_id := NEW.pos_transaction_id;
                    END IF;

                    SELECT
                        COALESCE(SUM(quantity * unit_price), 0),
                        COALESCE(SUM(line_total), 0),
                        COUNT(*)::integer
                    INTO
                        calculated_subtotal,
                        calculated_line_total,
                        calculated_items_count
                    FROM public.pos_transaction_items
                    WHERE pos_transaction_id = target_transaction_id;

                    SELECT COALESCE(SUM(amount), 0)
                    INTO calculated_paid
                    FROM public.pos_transaction_payments
                    WHERE pos_transaction_id = target_transaction_id
                      AND status = 'completed';

                    UPDATE public.pos_transactions
                    SET subtotal = calculated_subtotal,
                        items_count = calculated_items_count,
                        total = GREATEST(0, calculated_line_total - discount + tax),
                        payment_status = CASE
                            WHEN calculated_paid <= 0 THEN 'unpaid'
                            WHEN calculated_paid < GREATEST(0, calculated_line_total - discount + tax) THEN 'partial'
                            ELSE 'paid'
                        END,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = target_transaction_id;

                    IF TG_OP = 'DELETE' THEN
                        RETURN OLD;
                    END IF;

                    RETURN NEW;
                END;
                $$;

                CREATE TRIGGER resort_refresh_pos_after_item
                AFTER INSERT OR UPDATE OR DELETE
                ON public.pos_transaction_items
                FOR EACH ROW
                EXECUTE FUNCTION public.resort_refresh_pos_transaction();

                CREATE TRIGGER resort_refresh_pos_after_payment
                AFTER INSERT OR UPDATE OR DELETE
                ON public.pos_transaction_payments
                FOR EACH ROW
                EXECUTE FUNCTION public.resort_refresh_pos_transaction();
            SQL);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pos_transaction_payments');
        Schema::dropIfExists('pos_transaction_items');
        Schema::dropIfExists('pos_transactions');

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::statement('DROP FUNCTION IF EXISTS public.resort_refresh_pos_transaction()');
            DB::statement('DROP FUNCTION IF EXISTS public.resort_set_pos_item_line_total()');
        }
    }
};
