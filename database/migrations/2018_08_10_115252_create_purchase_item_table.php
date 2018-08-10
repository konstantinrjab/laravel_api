<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_item', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_purchase');
            $table->unsignedInteger('id_item');
            $table->unsignedInteger('number');
            $table->unsignedInteger('price');
            $table->timestamps();
    
            $table->foreign('id_purchase')
                ->references('id')
                ->on('parameters')
                ->onDelete('cascade');
            
            $table->foreign('id_item')
                ->references('id')
                ->on('items')
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
        Schema::dropIfExists('purchase_item');
    }
}
