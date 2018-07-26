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
		Schema::create('basic_form', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->date('birthday');
			$table->boolean('male')->default(false);
			$table->boolean('female')->default(false);
			$table->string('birthplace', 64);
			$table->string('address', 64);
			$table->string('phone1', 15);
			$table->string('phone2', 15);
			$table->string('email', 64);
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
		Schema::dropIfExists('basic_form');
	}
}
