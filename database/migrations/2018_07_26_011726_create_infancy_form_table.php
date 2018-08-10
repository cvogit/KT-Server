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
		Schema::create('infancy_forms', function (Blueprint $table) {
			$table->increments('id');
			$table->string('question_1', 32)->nullable(true)->default(null);
			$table->string('question_2', 32)->nullable(true)->default(null);
			$table->string('question_3', 32)->nullable(true)->default(null);
			$table->string('question_4', 32)->nullable(true)->default(null);
			$table->string('question_5', 32)->nullable(true)->default(null);
			$table->text('question_6')->nullable(true)->default(null);
			$table->text('question_7')->nullable(true)->default(null);
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
		Schema::dropIfExists('infancy_forms');
	}
}
