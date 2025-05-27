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
        Schema::table('caterings', function (Blueprint $table) {
            $table->dropColumn('photo');
        });

        Schema::table('caterings', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('price'); // 'nullable' oznacza, że zdjęcie nie jest wymagane
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('caterings', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
};
