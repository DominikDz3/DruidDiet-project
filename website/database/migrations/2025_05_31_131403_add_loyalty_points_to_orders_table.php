<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kolumna określająca metodę płatności
            $table->string('payment_method')->default('cash'); // Domyślnie 'cash'

            $table->integer('points_used')->default(0)->nullable();
            $table->integer('points_earned')->default(0)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'points_used', 'points_earned']);
        });
    }
};
