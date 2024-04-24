<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('old_id')->unique();
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->onDelete('cascade');
            $table->string('barcode')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->integer('amount')->default(0)->comment('Наличие на складе');
            $table->double('price', 8, 2)->default(0)->comment('Розничная цена');
            $table->text('text')->nullable()->comment('Описание');
            $table->enum('is_action', [0, 1])->default(0)->comment('Если акция - 1');
            $table->date('action_end_date')->nullable()->comment('Дата окончания акции');
            $table->double('action_price', 8, 2)->default(0)->comment('Цена по акции');
            $table->integer('is_new_item')->default(0)->comment('Если новый товар - 1');
            $table->string('title', 255)->nullable();
            $table->string('keywords', 510)->nullable();
            $table->string('description', 510)->nullable();
            $table->smallInteger('formula')->default(0);
            $table->integer('visite_counter')->default(0)->comment('Количество посещений');
            $table->smallInteger('for_sale')->default(1)->comment('1-продается, 0-снято с продаж');
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
        Schema::dropIfExists('items');
    }
}
