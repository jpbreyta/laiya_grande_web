<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaterSportsTable extends Migration
{
    public function up(): void
    {
        Schema::create('water_sports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('price_details');
            $table->integer('min_participants')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('water_sports');
    }
}
