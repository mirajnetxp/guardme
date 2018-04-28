<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateSecurityCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('security_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });
        // Insert some stuff
        DB::table('security_categories')->insert([
                ['name' => 'Close Protection'],
                ['name' => 'Secutiy Guard'],
                ['name' => 'Door Supervisor']
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('security_categories');
    }
}
