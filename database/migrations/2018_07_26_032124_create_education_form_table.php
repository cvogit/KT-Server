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
		Schema::create('education_form', function (Blueprint $table) {
			$table->increments('id');
			$table->text('question_1');
			$table->boolean('question_2')->default(false);
			$table->boolean('question_3')->default(false);
			$table->boolean('question_4')->default(false);
			$table->boolean('question_5')->default(false);
			$table->text('question_6');
			$table->text('question_7');
			$table->text('question_8');
			$table->text('question_9');
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
		Schema::dropIfExists('education_form');
	}
}
