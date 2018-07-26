<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('students', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('avatarId')->references('id')->on('images')->default(0);
			$table->integer('basicFormId')->references('id')->on('basic_form')->default(0);
			$table->integer('familyFormId')->references('id')->on('family_form')->default(0);
			$table->integer('pregnancyFormId')->references('id')->on('pregnancy_form')->default(0);
			$table->integer('birthFormId')->references('id')->on('birth_form')->default(0);
			$table->integer('infancyFormId')->references('id')->on('infancy_form')->default(0);
			$table->integer('toddlerFormId')->references('id')->on('toddler_form')->default(0);
			$table->integer('educationFormId')->references('id')->on('education_form')->default(0);
			$table->integer('presentFormId')->references('id')->on('innancy_form')->default(0);
			$table->boolean('active')->default(1);
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
		Schema::dropIfExists('students');
	}
}