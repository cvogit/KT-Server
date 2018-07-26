<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBirthFormTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('birth_form', function (Blueprint $table) {
			$table->increments('id');
			$table->string('question_1', 128);
			$table->string('question_2', 128);
			$table->string('question_3', 128);
			$table->string('question_4', 128);
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
		Schema::dropIfExists('birth_form');
	}
}
