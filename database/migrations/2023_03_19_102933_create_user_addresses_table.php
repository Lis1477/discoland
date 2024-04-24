<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->string('first_name', 50)->nullable();
            $table->string('second_name', 50)->nullable();
            $table->string('family_name', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('street', 100)->nullable();
            $table->string('house', 10)->nullable();
            $table->string('corpus', 10)->nullable();
            $table->string('flat', 10)->nullable();
            $table->string('entrance', 10)->nullable()->comment('Подъезд');
            $table->string('floor', 10)->nullable()->comment('Этаж');
            $table->tinyInteger('main')->default('0')->comment('1-главный, 0-остальные');
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
        Schema::dropIfExists('user_addresses');
    }
}
