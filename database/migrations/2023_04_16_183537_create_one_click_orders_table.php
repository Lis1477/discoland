<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOneClickOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('one_click_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0)->comment('id заказчика, 0 - не зарегистрирован');
            $table->integer('item_id')->comment('Код товара');
            $table->string('client_name', 100)->comment('Имя заказчика');
            $table->string('phone', 19)->comment('Телефон заказчика');
            $table->string('email', 100)->nullable()->comment('Email заказчика');
            $table->text('comment')->nullable()->comment('Комментарий');
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
        Schema::dropIfExists('one_click_orders');
    }
}
