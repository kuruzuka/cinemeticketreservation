<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movie_id')->constrained()->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('cinema_id')->constrained()->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('city_id')->constrained()->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('timeslot_id')->constrained()->noActionOnDelete()->noActionOnUpdate();
            $table->unique(['cinema_id','timeslot_id']);
            $table->unique(['city_id','movie_id','cinema_id','timeslot_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
