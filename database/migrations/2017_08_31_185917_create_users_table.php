<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 64);
			$table->string('email', 64)->unique();
			$table->string('password', 64);
			$table->string('phoneNum', 32);
			$table->string('profilePicPath', 64)->references('path')->on('images');
			$table->boolean('active')->default(0);
			$table->string('lastLogin');
			$table->timestampsTz();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}
}
