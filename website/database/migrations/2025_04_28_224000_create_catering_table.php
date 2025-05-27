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
        Schema::create('caterings', function (Blueprint $table) {
            $table->id('catering_id');
            $table->string('title', 50);
            $table->text('description');
            $table->string('type', 50);
            $table->text('elements');
            $table->decimal('price', 8, 2);
            $table->string('photo')->nullable();
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
        Schema::dropIfExists('caterings');
    }
};
