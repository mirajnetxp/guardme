<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('job_id')->default(0);
            $table->string('debit_credit_type');
            $table->float('amount',  8, 2)->nullable();
            $table->string('type');
            $table->string('title')->nullable();
            $table->integer('status')->default(0);
            $table->string('credit_payment_status')->nullable();
            $table->string('paypal_id')->nullable();
            $table->string('paypal_payment_status')->nullable();
            $table->longText('extra_details')->nullable();
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
        //
        Schema::dropIfExists('transactions');
    }
}
