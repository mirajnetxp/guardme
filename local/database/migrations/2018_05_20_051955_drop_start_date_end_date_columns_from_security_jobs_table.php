<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropStartDateEndDateColumnsFromSecurityJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('security_jobs', function (Blueprint $table) {
            $table->dropColumn('start_date_time');
            $table->dropColumn('end_date_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('security_jobs', function (Blueprint $table) {
            $table->timestamp('start_date_time')->nullable();
            $table->timestamp('end_date_time')->nullable();
        });
    }
}
