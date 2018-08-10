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
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('parameter_id');
            $table->string('value');
            $table->timestamps();
    
            $table->foreign('item_id')
                ->references('id')
                ->on('items')
                ->onDelete('cascade');
            
            $table->foreign('parameter_id')
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
