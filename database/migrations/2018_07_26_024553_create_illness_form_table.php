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
		Schema::create('illness_forms', function (Blueprint $table) {
			$table->increments('id');
			$table->boolean('question_1')->nullable(true)->default(null);
			$table->boolean('question_2')->nullable(true)->default(null);
			$table->boolean('question_3')->nullable(true)->default(null);
			$table->boolean('question_4')->nullable(true)->default(null);
			$table->boolean('question_5')->nullable(true)->default(null);
			$table->boolean('question_6')->nullable(true)->default(null);
			$table->boolean('question_7')->nullable(true)->default(null);
			$table->boolean('question_8')->nullable(true)->default(null);
			$table->boolean('question_9')->nullable(true)->default(null);
			$table->boolean('question_10')->nullable(true)->default(null);
			$table->boolean('question_11')->nullable(true)->default(null);
			$table->boolean('question_12')->nullable(true)->default(null);
			$table->boolean('question_13')->nullable(true)->default(null);
			$table->boolean('question_14')->nullable(true)->default(null);
			$table->boolean('question_15')->nullable(true)->default(null);
			$table->boolean('question_16')->nullable(true)->default(null);
			$table->text('question_17')->nullable(true)->default(null);
			$table->text('question_18')->nullable(true)->default(null);
			$table->boolean('question_19')->nullable(true)->default(null);
			$table->boolean('question_20')->nullable(true)->default(null);
			$table->boolean('question_21')->nullable(true)->default(null);
			$table->boolean('question_22')->nullable(true)->default(null);
			$table->boolean('question_23')->nullable(true)->default(null);
			$table->boolean('question_24')->nullable(true)->default(null);
			$table->boolean('question_25')->nullable(true)->default(null);
			$table->text('question_26')->nullable(true)->default(null);
			$table->text('question_27')->nullable(true)->default(null);
			$table->text('question_28')->nullable(true)->default(null);
			$table->text('question_29')->nullable(true)->default(null);
			$table->text('question_30')->nullable(true)->default(null);
			$table->text('question_31')->nullable(true)->default(null);
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
		Schema::dropIfExists('illness_forms');
	}
}
