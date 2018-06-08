<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('payment_requests', function(Blueprint $table){
            $table->increments('id');
            $table->string('description')->nullable()->default(null);
            $table->integer('application_id');
            $table->string('type')->nullable()->default('job_fee');
            $table->integer('number_of_hours');
            $table->string('status')->nullable()->default('pending');
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
        Schema::dropIfExists('payment_requests');
    }
}
