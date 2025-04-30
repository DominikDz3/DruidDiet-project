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
        Schema::create('diets', function (Blueprint $table) {
            $table->id('diet_id');
            $table->string('title', 50);
            $table->text('description');
            $table->string('type');
            $table->integer('calories');
            $table->text('elements');
            $table->decimal('price', 8, 2);
            $table->binary('photo');
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
        Schema::dropIfExists('diets');
    }
};
