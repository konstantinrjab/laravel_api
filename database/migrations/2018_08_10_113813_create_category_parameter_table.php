<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryParameterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_parameter', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_category');
            $table->unsignedInteger('id_parameter');
            $table->timestamps();
    
            $table->foreign('id_category')
                ->references('id')
                ->on('categories')
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
        Schema::dropIfExists('category_parameter');
    }
}
