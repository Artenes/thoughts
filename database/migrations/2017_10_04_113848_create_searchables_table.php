<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration to create searchables table.
 */
class CreateSearchablesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Searchables', function (Blueprint $table) {
            $table->increments('id');
            $table->text('body');
            $table->json('meta');
            $table->unsignedInteger('identifier');
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Searchables');
    }

}
