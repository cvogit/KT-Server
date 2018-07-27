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
			$table->string('name');
			$table->integer('avatarId')->references('id')->on('images')->default(0);
			$table->integer('basicFormId')->references('id')->on('basic_forms')->default(0);
			$table->integer('familyFormId')->references('id')->on('family_forms')->default(0);
			$table->integer('pregnancyFormId')->references('id')->on('pregnancy_forms')->default(0);
			$table->integer('birthFormId')->references('id')->on('birth_forms')->default(0);
			$table->integer('infancyFormId')->references('id')->on('infancy_forms')->default(0);
			$table->integer('toddlerFormId')->references('id')->on('toddler_forms')->default(0);
			$table->integer('illnessFormId')->references('id')->on('illness_forms')->default(0);
			$table->integer('educationFormId')->references('id')->on('education_forms')->default(0);
			$table->integer('presentFormId')->references('id')->on('present_forms')->default(0);
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