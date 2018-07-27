<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToddlerFormTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('toddler_forms', function (Blueprint $table) {
			$table->increments('id');
			$table->tinyInteger('question_1')->nullable(true)->default(null);
			$table->text('question_2')->nullable(true)->default(null);
			$table->text('question_3')->nullable(true)->default(null);
			$table->text('question_4')->nullable(true)->default(null);
			$table->text('question_5')->nullable(true)->default(null);
			$table->tinyInteger('question_6')->nullable(true)->default(null);
			$table->tinyInteger('question_7')->nullable(true)->default(null);
			$table->tinyInteger('question_8')->nullable(true)->default(null);
			$table->tinyInteger('question_9')->nullable(true)->default(null);
			$table->tinyInteger('question_10')->nullable(true)->default(null);
			$table->tinyInteger('question_11')->nullable(true)->default(null);
			$table->tinyInteger('question_12')->nullable(true)->default(null);
			$table->tinyInteger('question_13')->nullable(true)->default(null);
			$table->tinyInteger('question_14')->nullable(true)->default(null);
			$table->tinyInteger('question_15')->nullable(true)->default(null);
			$table->tinyInteger('question_16')->nullable(true)->default(null);
			$table->tinyInteger('question_17')->nullable(true)->default(null);
			$table->tinyInteger('question_18')->nullable(true)->default(null);
			$table->tinyInteger('question_19')->nullable(true)->default(null);
			$table->tinyInteger('question_20')->nullable(true)->default(null);
			$table->boolean('question_21')->nullable(true)->default(null);
			$table->boolean('question_22')->nullable(true)->default(null);
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
		Schema::dropIfExists('toddler_forms');
	}
}