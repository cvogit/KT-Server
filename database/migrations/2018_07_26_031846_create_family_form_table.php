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
		Schema::create('family_form', function (Blueprint $table) {
			$table->increments('id');
			$table->string('motherName');
			$table->date('motherBirthday');
			$table->string('motherEmployment', 64);
			$table->string('motherNationality', 64);
			$table->string('fatherName');
			$table->date('fatherBirthday');
			$table->string('fatherEmployment', 64);
			$table->string('fatherNationality', 64);
			$table->text('extraFamily');
			$table->boolean('parentTogether')->default(true);
			$table->boolean('familyIllness')->default(false);
			$table->text('familyIllnessDetails');
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
		Schema::dropIfExists('family_form');
	}
}
