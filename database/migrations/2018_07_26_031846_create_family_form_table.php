<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFamilyFormTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('family_forms', function (Blueprint $table) {
			$table->increments('id');
			$table->string('question_1', 64)->nullable(true)->default(null);
			$table->date('question_2')->nullable(true)->default(null);
			$table->string('question_3', 64)->nullable(true)->default(null);
			$table->string('question_4', 64)->nullable(true)->default(null);
			$table->string('question_5', 64)->nullable(true)->default(null);
			$table->date('question_6')->nullable(true)->default(null);
			$table->string('question_7', 64)->nullable(true)->default(null);
			$table->string('question_8', 64)->nullable(true)->default(null);
			$table->text('question_9')->nullable(true)->default(null);
			$table->boolean('question_10')->nullable(true)->default(null);
			$table->boolean('question_11')->nullable(true)->default(null);
			$table->text('question_12')->nullable(true)->default(null);
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
		Schema::dropIfExists('family_forms');
	}
}
