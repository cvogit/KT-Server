<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('teachers', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('userId')->references('id')->on('users')->unique();
			$table->tinyInteger('numStudents')->default(0);
			$table->tinyInteger('newReports')->default(0);
			$table->integer('totalReports')->default(0);
			$table->boolean('active')->default(1);
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
		Schema::dropIfExists('teachers');
	}
}