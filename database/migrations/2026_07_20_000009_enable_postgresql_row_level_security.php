<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $rlsTables = [
        'users',
        'roles',
        'role_user',
        'customers',
        'personal_access_tokens',
        'rooms',
        'room_rates',
        'amenities',
        'amenity_room',
        'room_images',
        'catalog_categories',
        'catalog_items',
        'bookings',
        'guest_stays',
        'payments',
        'otp_challenges',
        'room_ratings',
        'pos_transactions',
        'pos_transaction_items',
        'pos_transaction_payments',
        'policies',
        'contact_subjects',
        'contact_messages',
        'general_settings',
        'communication_settings',
        'notifications',
        'notification_deliveries',
        'data_access_logs',
    ];

    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        DB::unprepared(<<<'SQL'
            CREATE SCHEMA IF NOT EXISTS app_private;
            REVOKE CREATE ON SCHEMA app_private FROM PUBLIC;
            GRANT USAGE ON SCHEMA app_private TO PUBLIC;

            CREATE OR REPLACE FUNCTION app_private.current_auth_uid()
            RETURNS uuid
            LANGUAGE plpgsql
            STABLE
            SECURITY DEFINER
            SET search_path = public, pg_temp
            AS $$
            DECLARE
                raw_claims text;
                subject_value text;
            BEGIN
                raw_claims := current_setting('request.jwt.claims', true);

                IF raw_claims IS NOT NULL AND raw_claims <> '' THEN
                    subject_value := (raw_claims::jsonb)->>'sub';
                END IF;

                IF subject_value IS NULL OR subject_value = '' THEN
                    subject_value := nullif(current_setting('request.jwt.claim.sub', true), '');
                END IF;

                IF subject_value IS NULL OR subject_value = '' THEN
                    subject_value := nullif(current_setting('app.current_auth_uid', true), '');
                END IF;

                IF subject_value IS NULL OR subject_value = '' THEN
                    RETURN NULL;
                END IF;

                RETURN subject_value::uuid;
            EXCEPTION WHEN OTHERS THEN
                RETURN NULL;
            END;
            $$;

            CREATE OR REPLACE FUNCTION app_private.current_user_id()
            RETURNS bigint
            LANGUAGE plpgsql
            STABLE
            SECURITY DEFINER
            SET search_path = public, pg_temp
            AS $$
            DECLARE
                configured_id bigint;
                mapped_id bigint;
                auth_uid uuid;
            BEGIN
                configured_id := nullif(current_setting('app.current_user_id', true), '')::bigint;

                IF configured_id IS NOT NULL THEN
                    RETURN configured_id;
                END IF;

                auth_uid := app_private.current_auth_uid();

                IF auth_uid IS NULL THEN
                    RETURN NULL;
                END IF;

                SELECT id
                INTO mapped_id
                FROM public.users
                WHERE auth_user_id = auth_uid
                  AND deleted_at IS NULL
                  AND is_active = true
                LIMIT 1;

                RETURN mapped_id;
            EXCEPTION WHEN OTHERS THEN
                RETURN NULL;
            END;
            $$;

            CREATE OR REPLACE FUNCTION app_private.current_customer_id()
            RETURNS bigint
            LANGUAGE plpgsql
            STABLE
            SECURITY DEFINER
            SET search_path = public, pg_temp
            AS $$
            DECLARE
                configured_id bigint;
                mapped_id bigint;
                auth_uid uuid;
            BEGIN
                configured_id := nullif(current_setting('app.current_customer_id', true), '')::bigint;

                IF configured_id IS NOT NULL THEN
                    RETURN configured_id;
                END IF;

                auth_uid := app_private.current_auth_uid();

                IF auth_uid IS NULL THEN
                    RETURN NULL;
                END IF;

                SELECT id
                INTO mapped_id
                FROM public.customers
                WHERE auth_user_id = auth_uid
                  AND deleted_at IS NULL
                LIMIT 1;

                RETURN mapped_id;
            EXCEPTION WHEN OTHERS THEN
                RETURN NULL;
            END;
            $$;

            CREATE OR REPLACE FUNCTION app_private.has_role(required_role text)
            RETURNS boolean
            LANGUAGE sql
            STABLE
            SECURITY DEFINER
            SET search_path = public, pg_temp
            AS $$
                SELECT EXISTS (
                    SELECT 1
                    FROM public.role_user ru
                    INNER JOIN public.roles r ON r.id = ru.role_id
                    WHERE ru.user_id = app_private.current_user_id()
                      AND r.name = required_role
                );
            $$;

            CREATE OR REPLACE FUNCTION app_private.is_staff()
            RETURNS boolean
            LANGUAGE sql
            STABLE
            SECURITY DEFINER
            SET search_path = public, pg_temp
            AS $$
                SELECT EXISTS (
                    SELECT 1
                    FROM public.role_user ru
                    INNER JOIN public.roles r ON r.id = ru.role_id
                    WHERE ru.user_id = app_private.current_user_id()
                      AND r.name IN ('admin', 'manager', 'receptionist', 'staff')
                );
            $$;

            CREATE OR REPLACE FUNCTION app_private.is_admin()
            RETURNS boolean
            LANGUAGE sql
            STABLE
            SECURITY DEFINER
            SET search_path = public, pg_temp
            AS $$
                SELECT app_private.has_role('admin');
            $$;


            CREATE OR REPLACE FUNCTION app_private.protect_customer_booking_update()
            RETURNS trigger
            LANGUAGE plpgsql
            SECURITY DEFINER
            SET search_path = public, pg_temp
            AS $$
            BEGIN
                IF app_private.is_staff() THEN
                    RETURN NEW;
                END IF;

                IF NEW.booking_number IS DISTINCT FROM OLD.booking_number
                   OR NEW.customer_id IS DISTINCT FROM OLD.customer_id
                   OR NEW.source IS DISTINCT FROM OLD.source
                   OR NEW.quoted_total IS DISTINCT FROM OLD.quoted_total
                   OR NEW.created_by IS DISTINCT FROM OLD.created_by
                   OR NEW.updated_by IS DISTINCT FROM OLD.updated_by
                   OR NEW.actual_check_in_time IS DISTINCT FROM OLD.actual_check_in_time
                   OR NEW.actual_check_out_time IS DISTINCT FROM OLD.actual_check_out_time
                   OR NEW.deleted_at IS DISTINCT FROM OLD.deleted_at THEN
                    RAISE EXCEPTION 'Protected booking fields may only be changed by staff.';
                END IF;

                RETURN NEW;
            END;
            $$;

            DROP TRIGGER IF EXISTS protect_customer_booking_update ON public.bookings;
            CREATE TRIGGER protect_customer_booking_update
            BEFORE UPDATE ON public.bookings
            FOR EACH ROW
            EXECUTE FUNCTION app_private.protect_customer_booking_update();

            CREATE OR REPLACE FUNCTION app_private.protect_recipient_notification_update()
            RETURNS trigger
            LANGUAGE plpgsql
            SECURITY DEFINER
            SET search_path = public, pg_temp
            AS $$
            BEGIN
                IF app_private.is_staff() THEN
                    RETURN NEW;
                END IF;

                IF NEW.user_id IS DISTINCT FROM OLD.user_id
                   OR NEW.customer_id IS DISTINCT FROM OLD.customer_id
                   OR NEW.is_broadcast IS DISTINCT FROM OLD.is_broadcast
                   OR NEW.type IS DISTINCT FROM OLD.type
                   OR NEW.title IS DISTINCT FROM OLD.title
                   OR NEW.message IS DISTINCT FROM OLD.message
                   OR NEW.data IS DISTINCT FROM OLD.data
                   OR NEW.created_at IS DISTINCT FROM OLD.created_at THEN
                    RAISE EXCEPTION 'Recipients may only update notification read state.';
                END IF;

                RETURN NEW;
            END;
            $$;

            DROP TRIGGER IF EXISTS protect_recipient_notification_update ON public.notifications;
            CREATE TRIGGER protect_recipient_notification_update
            BEFORE UPDATE ON public.notifications
            FOR EACH ROW
            EXECUTE FUNCTION app_private.protect_recipient_notification_update();

            CREATE OR REPLACE FUNCTION app_private.protect_customer_rating_update()
            RETURNS trigger
            LANGUAGE plpgsql
            SECURITY DEFINER
            SET search_path = public, pg_temp
            AS $$
            BEGIN
                IF app_private.is_staff() THEN
                    RETURN NEW;
                END IF;

                IF NEW.booking_id IS DISTINCT FROM OLD.booking_id
                   OR NEW.is_verified IS DISTINCT FROM OLD.is_verified
                   OR NEW.moderated_at IS DISTINCT FROM OLD.moderated_at
                   OR NEW.moderated_by IS DISTINCT FROM OLD.moderated_by
                   OR NEW.created_at IS DISTINCT FROM OLD.created_at THEN
                    RAISE EXCEPTION 'Protected rating fields may only be changed by staff.';
                END IF;

                RETURN NEW;
            END;
            $$;

            DROP TRIGGER IF EXISTS protect_customer_rating_update ON public.room_ratings;
            CREATE TRIGGER protect_customer_rating_update
            BEFORE UPDATE ON public.room_ratings
            FOR EACH ROW
            EXECUTE FUNCTION app_private.protect_customer_rating_update();
        SQL);

        foreach ($this->rlsTables as $table) {
            DB::statement("ALTER TABLE public.{$table} ENABLE ROW LEVEL SECURITY");
        }

        $staffManagedTables = [
            'customers',
            'rooms',
            'room_rates',
            'amenities',
            'amenity_room',
            'room_images',
            'catalog_categories',
            'catalog_items',
            'bookings',
            'guest_stays',
            'payments',
            'otp_challenges',
            'room_ratings',
            'pos_transactions',
            'pos_transaction_items',
            'pos_transaction_payments',
            'policies',
            'contact_subjects',
            'contact_messages',
            'notifications',
            'notification_deliveries',
        ];

        foreach ($staffManagedTables as $table) {
            DB::statement("CREATE POLICY resort_staff_all ON public.{$table} FOR ALL TO PUBLIC USING (app_private.is_staff()) WITH CHECK (app_private.is_staff())");
        }

        foreach (['users', 'roles', 'role_user', 'communication_settings'] as $table) {
            DB::statement("CREATE POLICY resort_admin_all ON public.{$table} FOR ALL TO PUBLIC USING (app_private.is_admin()) WITH CHECK (app_private.is_admin())");
        }

        DB::unprepared(<<<'SQL'
            CREATE POLICY resort_users_own_select
            ON public.users FOR SELECT TO PUBLIC
            USING (id = app_private.current_user_id());

            CREATE POLICY resort_roles_staff_select
            ON public.roles FOR SELECT TO PUBLIC
            USING (app_private.is_staff());

            CREATE POLICY resort_role_user_own_select
            ON public.role_user FOR SELECT TO PUBLIC
            USING (user_id = app_private.current_user_id());

            CREATE POLICY resort_customers_own_select
            ON public.customers FOR SELECT TO PUBLIC
            USING (id = app_private.current_customer_id());

            CREATE POLICY resort_customers_own_insert
            ON public.customers FOR INSERT TO PUBLIC
            WITH CHECK (
                app_private.current_auth_uid() IS NOT NULL
                AND auth_user_id = app_private.current_auth_uid()
            );

            CREATE POLICY resort_customers_own_update
            ON public.customers FOR UPDATE TO PUBLIC
            USING (id = app_private.current_customer_id())
            WITH CHECK (
                id = app_private.current_customer_id()
                AND auth_user_id = app_private.current_auth_uid()
            );

            CREATE POLICY resort_rooms_public_select
            ON public.rooms FOR SELECT TO PUBLIC
            USING (deleted_at IS NULL AND status = 'available');

            CREATE POLICY resort_room_rates_public_select
            ON public.room_rates FOR SELECT TO PUBLIC
            USING (
                is_active = true
                AND (starts_on IS NULL OR starts_on <= CURRENT_DATE)
                AND (ends_on IS NULL OR ends_on >= CURRENT_DATE)
            );

            CREATE POLICY resort_amenities_public_select
            ON public.amenities FOR SELECT TO PUBLIC
            USING (true);

            CREATE POLICY resort_amenity_room_public_select
            ON public.amenity_room FOR SELECT TO PUBLIC
            USING (true);

            CREATE POLICY resort_room_images_public_select
            ON public.room_images FOR SELECT TO PUBLIC
            USING (
                EXISTS (
                    SELECT 1
                    FROM public.rooms r
                    WHERE r.id = room_images.room_id
                      AND r.deleted_at IS NULL
                      AND r.status = 'available'
                )
            );

            CREATE POLICY resort_catalog_categories_public_select
            ON public.catalog_categories FOR SELECT TO PUBLIC
            USING (is_active = true);

            CREATE POLICY resort_catalog_items_public_select
            ON public.catalog_items FOR SELECT TO PUBLIC
            USING (deleted_at IS NULL AND is_available = true);

            CREATE POLICY resort_bookings_customer_select
            ON public.bookings FOR SELECT TO PUBLIC
            USING (customer_id = app_private.current_customer_id());

            CREATE POLICY resort_bookings_customer_insert
            ON public.bookings FOR INSERT TO PUBLIC
            WITH CHECK (
                app_private.current_customer_id() IS NOT NULL
                AND customer_id = app_private.current_customer_id()
                AND source = 'online'
                AND status = 'pending'
                AND quoted_total = 0
                AND created_by IS NULL
                AND updated_by IS NULL
            );

            CREATE POLICY resort_bookings_customer_update
            ON public.bookings FOR UPDATE TO PUBLIC
            USING (
                customer_id = app_private.current_customer_id()
                AND status = 'pending'
            )
            WITH CHECK (
                customer_id = app_private.current_customer_id()
                AND status IN ('pending', 'cancelled')
                AND created_by IS NULL
                AND updated_by IS NULL
            );

            CREATE POLICY resort_guest_stays_customer_select
            ON public.guest_stays FOR SELECT TO PUBLIC
            USING (
                EXISTS (
                    SELECT 1
                    FROM public.bookings b
                    WHERE b.id = guest_stays.booking_id
                      AND b.customer_id = app_private.current_customer_id()
                )
            );

            CREATE POLICY resort_payments_customer_select
            ON public.payments FOR SELECT TO PUBLIC
            USING (
                EXISTS (
                    SELECT 1
                    FROM public.bookings b
                    WHERE b.id = payments.booking_id
                      AND b.customer_id = app_private.current_customer_id()
                )
            );

            CREATE POLICY resort_room_ratings_public_select
            ON public.room_ratings FOR SELECT TO PUBLIC
            USING (is_verified = true);

            CREATE POLICY resort_room_ratings_customer_insert
            ON public.room_ratings FOR INSERT TO PUBLIC
            WITH CHECK (
                is_verified = false
                AND moderated_at IS NULL
                AND moderated_by IS NULL
                AND EXISTS (
                    SELECT 1
                    FROM public.bookings b
                    WHERE b.id = room_ratings.booking_id
                      AND b.customer_id = app_private.current_customer_id()
                      AND b.status = 'completed'
                )
            );

            CREATE POLICY resort_room_ratings_customer_update
            ON public.room_ratings FOR UPDATE TO PUBLIC
            USING (
                is_verified = false
                AND EXISTS (
                    SELECT 1
                    FROM public.bookings b
                    WHERE b.id = room_ratings.booking_id
                      AND b.customer_id = app_private.current_customer_id()
                )
            )
            WITH CHECK (
                is_verified = false
                AND moderated_at IS NULL
                AND moderated_by IS NULL
                AND EXISTS (
                    SELECT 1
                    FROM public.bookings b
                    WHERE b.id = room_ratings.booking_id
                      AND b.customer_id = app_private.current_customer_id()
                )
            );

            CREATE POLICY resort_pos_transactions_customer_select
            ON public.pos_transactions FOR SELECT TO PUBLIC
            USING (
                customer_id = app_private.current_customer_id()
                OR EXISTS (
                    SELECT 1
                    FROM public.guest_stays gs
                    INNER JOIN public.bookings b ON b.id = gs.booking_id
                    WHERE gs.id = pos_transactions.guest_stay_id
                      AND b.customer_id = app_private.current_customer_id()
                )
            );

            CREATE POLICY resort_pos_items_customer_select
            ON public.pos_transaction_items FOR SELECT TO PUBLIC
            USING (
                EXISTS (
                    SELECT 1
                    FROM public.pos_transactions pt
                    WHERE pt.id = pos_transaction_items.pos_transaction_id
                      AND (
                          pt.customer_id = app_private.current_customer_id()
                          OR EXISTS (
                              SELECT 1
                              FROM public.guest_stays gs
                              INNER JOIN public.bookings b ON b.id = gs.booking_id
                              WHERE gs.id = pt.guest_stay_id
                                AND b.customer_id = app_private.current_customer_id()
                          )
                      )
                )
            );

            CREATE POLICY resort_pos_payments_customer_select
            ON public.pos_transaction_payments FOR SELECT TO PUBLIC
            USING (
                EXISTS (
                    SELECT 1
                    FROM public.pos_transactions pt
                    WHERE pt.id = pos_transaction_payments.pos_transaction_id
                      AND (
                          pt.customer_id = app_private.current_customer_id()
                          OR EXISTS (
                              SELECT 1
                              FROM public.guest_stays gs
                              INNER JOIN public.bookings b ON b.id = gs.booking_id
                              WHERE gs.id = pt.guest_stay_id
                                AND b.customer_id = app_private.current_customer_id()
                          )
                      )
                )
            );

            CREATE POLICY resort_policies_public_select
            ON public.policies FOR SELECT TO PUBLIC
            USING (
                is_active = true
                AND (effective_at IS NULL OR effective_at <= CURRENT_TIMESTAMP)
            );

            CREATE POLICY resort_contact_subjects_public_select
            ON public.contact_subjects FOR SELECT TO PUBLIC
            USING (is_active = true);

            CREATE POLICY resort_contact_messages_public_insert
            ON public.contact_messages FOR INSERT TO PUBLIC
            WITH CHECK (
                status = 'unread'
                AND read_at IS NULL
                AND replied_at IS NULL
                AND replied_by IS NULL
                AND archived_at IS NULL
            );

            CREATE POLICY resort_general_settings_public_select
            ON public.general_settings FOR SELECT TO PUBLIC
            USING (true);

            CREATE POLICY resort_general_settings_admin_all
            ON public.general_settings FOR ALL TO PUBLIC
            USING (app_private.is_admin())
            WITH CHECK (app_private.is_admin());

            CREATE POLICY resort_notifications_recipient_select
            ON public.notifications FOR SELECT TO PUBLIC
            USING (
                is_broadcast = true
                OR user_id = app_private.current_user_id()
                OR customer_id = app_private.current_customer_id()
            );

            CREATE POLICY resort_notifications_recipient_update
            ON public.notifications FOR UPDATE TO PUBLIC
            USING (
                user_id = app_private.current_user_id()
                OR customer_id = app_private.current_customer_id()
            )
            WITH CHECK (
                is_broadcast = false
                AND (
                    user_id = app_private.current_user_id()
                    OR customer_id = app_private.current_customer_id()
                )
            );

            CREATE POLICY resort_notification_deliveries_recipient_select
            ON public.notification_deliveries FOR SELECT TO PUBLIC
            USING (
                EXISTS (
                    SELECT 1
                    FROM public.notifications n
                    WHERE n.id = notification_deliveries.notification_id
                      AND (
                          n.is_broadcast = true
                          OR n.user_id = app_private.current_user_id()
                          OR n.customer_id = app_private.current_customer_id()
                      )
                )
            );

            CREATE POLICY resort_data_access_logs_staff_insert
            ON public.data_access_logs FOR INSERT TO PUBLIC
            WITH CHECK (app_private.is_staff());

            CREATE POLICY resort_data_access_logs_admin_select
            ON public.data_access_logs FOR SELECT TO PUBLIC
            USING (app_private.is_admin());
        SQL);

        $allApplicationTables = implode(', ', array_map(
            static fn (string $table): string => "public.{$table}",
            $this->rlsTables
        ));

        DB::unprepared(<<<SQL
            DO \$\$
            BEGIN
                IF EXISTS (SELECT 1 FROM pg_roles WHERE rolname = 'anon') THEN
                    GRANT SELECT ON TABLE
                        public.rooms,
                        public.room_rates,
                        public.amenities,
                        public.amenity_room,
                        public.room_images,
                        public.catalog_categories,
                        public.catalog_items,
                        public.room_ratings,
                        public.policies,
                        public.contact_subjects,
                        public.general_settings
                    TO anon;

                    GRANT INSERT ON TABLE public.contact_messages TO anon;
                    GRANT USAGE, SELECT ON SEQUENCE public.contact_messages_id_seq TO anon;
                END IF;

                IF EXISTS (SELECT 1 FROM pg_roles WHERE rolname = 'authenticated') THEN
                    GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE {$allApplicationTables} TO authenticated;
                    GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO authenticated;
                    GRANT USAGE ON SCHEMA app_private TO authenticated;
                END IF;

                IF EXISTS (SELECT 1 FROM pg_roles WHERE rolname = 'service_role') THEN
                    GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE {$allApplicationTables} TO service_role;
                    GRANT USAGE, SELECT ON ALL SEQUENCES IN SCHEMA public TO service_role;
                    GRANT USAGE ON SCHEMA app_private TO service_role;
                END IF;
            END
            \$\$;
        SQL);
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        $policies = DB::select(<<<'SQL'
            SELECT tablename, policyname
            FROM pg_policies
            WHERE schemaname = 'public'
              AND policyname LIKE 'resort_%'
        SQL);

        foreach ($policies as $policy) {
            $table = str_replace('"', '""', $policy->tablename);
            $name = str_replace('"', '""', $policy->policyname);
            DB::statement("DROP POLICY IF EXISTS \"{$name}\" ON public.\"{$table}\"");
        }

        foreach ($this->rlsTables as $table) {
            DB::statement("ALTER TABLE public.{$table} DISABLE ROW LEVEL SECURITY");
        }

        DB::statement('DROP SCHEMA IF EXISTS app_private CASCADE');
    }
};
