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
			$table->string('question_1', 64)->nullable(true)->default(null);
			$table->text('question_2')->nullable(true)->default(null);
			$table->text('question_3')->nullable(true)->default(null);
			$table->text('question_4')->nullable(true)->default(null);
			$table->text('question_5')->nullable(true)->default(null);
			$table->string('question_6', 64)->nullable(true)->default(null);
			$table->string('question_7', 64)->nullable(true)->default(null);
			$table->string('question_8', 64)->nullable(true)->default(null);
			$table->string('question_9', 64)->nullable(true)->default(null);
			$table->string('question_10', 64)->nullable(true)->default(null);
			$table->string('question_11', 64)->nullable(true)->default(null);
			$table->string('question_12', 64)->nullable(true)->default(null);
			$table->string('question_13', 64)->nullable(true)->default(null);
			$table->string('question_14', 64)->nullable(true)->default(null);
			$table->string('question_15', 64)->nullable(true)->default(null);
			$table->string('question_16', 64)->nullable(true)->default(null);
			$table->string('question_17', 64)->nullable(true)->default(null);
			$table->string('question_18', 64)->nullable(true)->default(null);
			$table->string('question_19', 64)->nullable(true)->default(null);
			$table->string('question_20', 64)->nullable(true)->default(null);
			$table->boolean('question_21')->nullable(true)->default(null);
			$table->text('question_22')->nullable(true)->default(null);
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