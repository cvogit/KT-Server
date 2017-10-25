<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentReportsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('student_reports', function (Blueprint $table)    {
			$table->increments('id');
			$table->integer('teacherId')->references('id')->on('teachers');
			$table->integer('studentId')->references('id')->on('students');
			$table->string('goals');
			$table->string('results');
			$table->integer('score');
			$table->text('notes');
			$table->integer('lastReport');
			$table->integer('nextReport');
			$table->string('deadline');
			$table->string('submitTime');
			$table->string('weekOf');
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
		Schema::dropIfExists('student_reports');
	}
}
