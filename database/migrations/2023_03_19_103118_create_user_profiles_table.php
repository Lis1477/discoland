<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->string('client_type', 50)->nullable()->comment('Физическое / Юридическое лицо');
            $table->string('gender', 10)->nullable()->comment('Пол');
            $table->date('birthday')->nullable()->comment('Дата рождения');
            $table->string('company_name', 255)->nullable();
            $table->string('unp', 10)->nullable();
            $table->text('requisites')->nullable();
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
        Schema::dropIfExists('user_profiles');
    }
}
