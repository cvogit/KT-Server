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
		Schema::create('pregnancy_form', function (Blueprint $table) {
			$table->increments('id');
			$table->text('question_1');
			$table->text('question_2');
			$table->boolean('question_3')->default(false);
			$table->boolean('question_4')->default(false);
			$table->boolean('question_5')->default(false);
			$table->boolean('question_6')->default(false);
			$table->boolean('question_7')->default(false);
			$table->boolean('question_8')->default(false);
			$table->boolean('question_9')->default(false);
			$table->boolean('question_10')->default(false);
			$table->boolean('question_11')->default(false);
			$table->text('question_12');
			$table->text('question_13');
			$table->text('question_14');
			$table->text('question_15');
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
		Schema::dropIfExists('pregnancy_form');
	}
}
