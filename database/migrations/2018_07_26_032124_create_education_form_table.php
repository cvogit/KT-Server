<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEducationFormTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('education_forms', function (Blueprint $table) {
			$table->increments('id');
			$table->text('question_1')->nullable(true)->default(null);
			$table->boolean('question_2')->nullable(true)->default(null);
			$table->boolean('question_3')->nullable(true)->default(null);
			$table->boolean('question_4')->nullable(true)->default(null);
			$table->boolean('question_5')->nullable(true)->default(null);
			$table->text('question_6')->nullable(true)->default(null);
			$table->text('question_7')->nullable(true)->default(null);
			$table->text('question_8')->nullable(true)->default(null);
			$table->text('question_9')->nullable(true)->default(null);
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
		Schema::dropIfExists('education_forms');
	}
}
