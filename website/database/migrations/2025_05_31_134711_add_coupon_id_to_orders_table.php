<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->foreignId('coupon_id')->nullable()->after('payment_method')->constrained('coupons', 'coupon_id')->nullOnDelete();
        $table->string('applied_coupon_code')->nullable()->after('coupon_id'); // Możesz też przechowywać sam kod
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
   public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        // Ważne: usunięcie klucza obcego przed usunięciem kolumny
        $table->dropForeign(['coupon_id']);
        $table->dropColumn(['coupon_id', 'applied_coupon_code']);
    });
}
};
