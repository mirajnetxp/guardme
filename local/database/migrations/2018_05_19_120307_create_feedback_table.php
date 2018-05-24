<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::dropIfExists('feedback');
        Schema::create('feedback', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id');
            $table->integer('appearance')->default(1);
            $table->integer('punctuality')->default(1);
            $table->integer('customer_focused')->default(1);
            $table->integer('security_conscious')->default(1);
            $table->text('message')->nullable()->default(null);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::dropIfExists('feedback');
    }
}
