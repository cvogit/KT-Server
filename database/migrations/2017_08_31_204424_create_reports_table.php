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
			$table->integer('teacherId')->references('id')->on('teachers');
			$table->integer('studentId')->references('id')->on('students');
			$table->string('goals', 256);
			$table->string('results', 256);
			$table->text('notes');
			$table->integer('score');
			$table->boolean('approve')->default('0');
			$table->boolean('update')->default('0');
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
