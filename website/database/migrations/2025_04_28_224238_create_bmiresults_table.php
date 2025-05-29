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
        Schema::create('bmi_results', function (Blueprint $table) {
            $table->id('bmi_result_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->decimal('bmi_value', 5, 2);
            $table->date('created_at');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bmi_results');
    }
};
