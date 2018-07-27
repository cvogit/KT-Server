<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePregnancyFormTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pregnancy_forms', function (Blueprint $table) {
			$table->increments('id');
			$table->text('question_1')->nullable(true)->default(null);
			$table->text('question_2')->nullable(true)->default(null);
			$table->boolean('question_3')->nullable(true)->default(null);
			$table->boolean('question_4')->nullable(true)->default(null);
			$table->boolean('question_5')->nullable(true)->default(null);
			$table->boolean('question_6')->nullable(true)->default(null);
			$table->boolean('question_7')->nullable(true)->default(null);
			$table->boolean('question_8')->nullable(true)->default(null);
			$table->boolean('question_9')->nullable(true)->default(null);
			$table->boolean('question_10')->nullable(true)->default(null);
			$table->boolean('question_11')->nullable(true)->default(null);
			$table->text('question_12')->nullable(true)->default(null);
			$table->text('question_13')->nullable(true)->default(null);
			$table->text('question_14')->nullable(true)->default(null);
			$table->text('question_15')->nullable(true)->default(null);
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
		Schema::dropIfExists('pregnancy_forms');
	}
}
