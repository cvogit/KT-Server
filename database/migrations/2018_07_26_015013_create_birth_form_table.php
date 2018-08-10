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
		Schema::create('birth_forms', function (Blueprint $table) {
			$table->increments('id');
			$table->string('question_1', 128)->nullable(true)->default(null);
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
		Schema::dropIfExists('birth_forms');
	}
}
