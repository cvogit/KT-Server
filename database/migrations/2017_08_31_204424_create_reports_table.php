<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reports', function (Blueprint $table)    {
			$table->increments('id');
			$table->integer('userId')->references('id')->on('users');
			$table->integer('studentId')->references('id')->on('students');
			$table->text('content_1');
			$table->text('content_2');
			$table->text('content_3');
			$table->integer('update')->default('0');
			$table->boolean('new')->default(true);
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
		Schema::dropIfExists('reports');
	}
}
