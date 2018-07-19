<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GpsToFreelancerSettings extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table( 'freelancer_settings', function ( Blueprint $table ) {
			$table->boolean( 'gps' ); // add this collumn to documents Verification
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table( 'freelancer_settings', function ( Blueprint $table ) {
			//
		} );
	}
}
