<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('old_id')->unique();
            $table->integer('parent_id')->default(0);
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->string('image', 100);
            $table->text('text')->nullable();
            $table->string('title', 255)->nullable();
            $table->string('keywords', 510)->nullable();
            $table->string('description', 510)->nullable();
            $table->tinyInteger('order')->default(1)->comment('Сортировка');
            $table->enum('display', [0, 1])->default(1)->comment('1-отображаем, 0-скрываем');
            $table->enum('in_header', [0, 1])->default(0)->comment('Если 1, выводится в хэдере');
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
        Schema::dropIfExists('categories');
    }
}
