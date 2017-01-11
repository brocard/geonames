<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->char('iso_code2', 2)->unique()->comment('ISO Alpha-2');
            $table->char('iso_code3', 3)->comment('ISO Alpha-3');
            $table->string('lang', 10)->nullable();
            $table->string('language', 10)->nullable()->default('en');
            $table->string('currency_code');
            $table->smallInteger('status')->default(1);
            $table->timestamps();
            $table->integer('geonameId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
