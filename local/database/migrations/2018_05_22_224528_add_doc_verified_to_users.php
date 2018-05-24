<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocVerifiedToUsers extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table( 'users', function ( $table ) {
			$table->boolean( 'doc_verified' ); // add this collumn to documents Verification
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table( 'users', function ( $table ) {
			$table->dropColumn( 'doc_verified' );
		} );
	}
}
