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
			$table->string('name')->nullable(true)->default(null);
			$table->date('birthday')->nullable(true)->default(null);
			$table->boolean('male')->nullable(true)->default(null);
			$table->boolean('female')->nullable(true)->default(null);
			$table->string('birthplace', 64)->nullable(true)->default(null);
			$table->string('address', 64)->nullable(true)->default(null);
			$table->string('phone1', 15)->nullable(true)->default(null);
			$table->string('phone2', 15)->nullable(true)->default(null);
			$table->string('email', 64)->nullable(true)->default(null);
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
