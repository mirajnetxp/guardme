<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTrackingTable extends Migration {

	public function up()
	{
		Schema::create('tracking', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('job_id');
			$table->integer('user_id');
			$table->string('location_longitude');
			$table->string('location_latitude');
			$table->string('address');
			$table->string('date_time');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('tracking');
	}
}