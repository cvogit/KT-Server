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
			$table->tinyInteger('question_1')->nullable(true)->default(null);
			$table->tinyInteger('question_2')->nullable(true)->default(null);
			$table->tinyInteger('question_3')->nullable(true)->default(null);
			$table->text('question_4')->nullable(true)->default(null);
			$table->string('question_5', 128)->nullable(true)->default(null);
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
