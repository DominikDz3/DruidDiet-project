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
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('email', 50);
            $table->string('password');
            $table->string('name', 30);
            $table->string('surname', 50);
            $table->string('role', 15);
            $table->text('TOTP_secret')->nullable();
            $table->decimal('loyalty_points', 10, 0)->nullable();
            $table->text('allergens')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
