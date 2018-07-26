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
		Schema::create('toddler_form', function (Blueprint $table) {
			$table->increments('id');
			$table->tinyInteger('question_1');
			$table->text('question_2');
			$table->text('question_3');
			$table->text('question_4');
			$table->text('question_5');
			$table->tinyInteger('question_6');
			$table->tinyInteger('question_7');
			$table->tinyInteger('question_8');
			$table->tinyInteger('question_9');
			$table->tinyInteger('question_10');
			$table->tinyInteger('question_11');
			$table->tinyInteger('question_12');
			$table->tinyInteger('question_13');
			$table->tinyInteger('question_14');
			$table->tinyInteger('question_15');
			$table->tinyInteger('question_16');
			$table->tinyInteger('question_17');
			$table->tinyInteger('question_18');
			$table->tinyInteger('question_19');
			$table->tinyInteger('question_20');
			$table->boolean('question_21')->default(false);
			$table->boolean('question_22')->default(false);
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
		Schema::dropIfExists('toddler_form');
	}
}