<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSecurityJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('security_jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('address_line3')->nullable();
            $table->string('locality')->nullable();
            $table->string('city_town')->nullable();
            $table->string('post_code')->nullable();
            $table->string('country')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->integer('daily_working_hours')->nullable();
            $table->integer('monthly_working_days')->nullable();
            $table->float('per_hour_rate',  8, 2)->nullable();
            $table->string('wallet_debit_frequency')->nullable();
            $table->boolean('visible_to_all_security_personal')->nullable();
            $table->boolean('visible_to_favourite')->nullable();
            $table->string('specific_area_min')->nullable();
            $table->string('specific_area_max')->nullable();
            $table->string('specific_category_id')->nullable();
            $table->integer('security_category_id')->nullable();
            $table->integer('business_category_id')->nullable();
            $table->integer('created_by');
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('security_jobs');
    }
}
