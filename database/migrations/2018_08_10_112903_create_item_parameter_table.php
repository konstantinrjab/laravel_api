<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemParameterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_parameter', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_item');
            $table->unsignedInteger('id_parameter');
            $table->string('value');
            $table->timestamps();
    
            $table->foreign('id_item')
                ->references('id')
                ->on('items')
                ->onDelete('cascade');
            
            $table->foreign('id_parameter')
                ->references('id')
                ->on('parameters')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_parameter');
    }
}
