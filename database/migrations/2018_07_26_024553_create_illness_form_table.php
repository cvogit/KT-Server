<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIllnessFormTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('illness_form', function (Blueprint $table) {
			$table->increments('id');
			$table->text('question_1');
			$table->boolean('question_2')->default(false);
			$table->boolean('question_3')->default(false);
			$table->boolean('question_4')->default(false);
			$table->boolean('question_5')->default(false);
			$table->boolean('question_6')->default(false);
			$table->boolean('question_7')->default(false);
			$table->boolean('question_8')->default(false);
			$table->boolean('question_9')->default(false);
			$table->boolean('question_10')->default(false);
			$table->boolean('question_11')->default(false);
			$table->boolean('question_12')->default(false);
			$table->boolean('question_13')->default(false);
			$table->boolean('question_14')->default(false);
			$table->boolean('question_15')->default(false);
			$table->boolean('question_16')->default(false);
			$table->text('question_17');
			$table->tinyInteger('question_18');
			$table->text('question_19');
			$table->boolean('question_20')->default(false);
			$table->boolean('question_21')->default(false);
			$table->boolean('question_22')->default(false);
			$table->boolean('question_23')->default(false);
			$table->boolean('question_24')->default(false);
			$table->boolean('question_25')->default(false);
			$table->text('question_26');
			$table->text('question_27');
			$table->text('question_28');
			$table->text('question_29');
			$table->text('question_30');
			$table->text('question_31');
			$table->text('question_32');
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
		Schema::dropIfExists('illness_form');
	}
}
