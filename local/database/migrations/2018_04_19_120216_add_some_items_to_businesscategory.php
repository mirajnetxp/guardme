<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddSomeItemsToBusinesscategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert some stuff
        DB::table('businesscategory')->insert([
                ['name' => 'Hospital'],
                ['name' => 'Club'],
                ['name' => 'Bar'],
                ['name' => 'Shop']
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
        //
        DB::table('businesscategory')
            ->whereIn('name', ['Hospital', 'Club', 'Bar', 'Shop'])->delete();
    }
}
