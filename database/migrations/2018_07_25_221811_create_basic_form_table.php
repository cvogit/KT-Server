<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasicFormTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('basic_forms', function (Blueprint $table) {
			$table->increments('id');
			$table->string('question_1', 64)->nullable(true)->default(null);
			$table->date('question_2')->nullable(true)->default(null);
			$table->boolean('question_3')->nullable(true)->default(null);
			$table->boolean('question_4')->nullable(true)->default(null);
			$table->string('question_5', 64)->nullable(true)->default(null);
			$table->string('question_6', 64)->nullable(true)->default(null);
			$table->string('question_7', 16)->nullable(true)->default(null);
			$table->string('question_8', 16)->nullable(true)->default(null);
			$table->string('question_9', 64)->nullable(true)->default(null);
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
		Schema::dropIfExists('basic_forms');
	}
}
