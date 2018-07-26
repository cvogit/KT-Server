<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfancyFormTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('infancy_form', function (Blueprint $table) {
			$table->increments('id');
			$table->tinyInteger('question_1');
			$table->tinyInteger('question_2');
			$table->tinyInteger('question_3');
			$table->text('question_4');
			$table->string('question_5', 128);
			$table->text('question_6');
			$table->text('question_7');
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
		Schema::dropIfExists('infancy_form');
	}
}
