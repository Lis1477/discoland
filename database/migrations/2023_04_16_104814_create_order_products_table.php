<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->foreign('order_id')
                ->references('id')->on('items')
                ->onDelete('cascade');
            $table->integer('item_id')->unsigned();
            $table->foreign('item_id')
                ->references('id')->on('items')
                ->onDelete('cascade');
            $table->string('name', 255);
            $table->integer('amount');
            $table->double('price', 8, 2);
            $table->double('economy', 8, 2)->default(0)->comment('Скидка');
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
        Schema::dropIfExists('order_products');
    }
}
