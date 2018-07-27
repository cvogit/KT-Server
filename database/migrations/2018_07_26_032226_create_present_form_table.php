<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresentFormTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('present_forms', function (Blueprint $table) {
			$table->increments('id');
			$table->text('question_1')->nullable(true)->default(null);
			$table->text('question_2')->nullable(true)->default(null);
			$table->text('question_3')->nullable(true)->default(null);
			$table->text('question_4')->nullable(true)->default(null);
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
		Schema::dropIfExists('present_forms');
	}
}
