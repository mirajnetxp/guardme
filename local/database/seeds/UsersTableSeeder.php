<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Partner',
            'email' => 'partner@example.com',
            'password' => bcrypt('123456'),
            'verified' => '0',
            'gender' => 'male',
            'phone' => '9961940646',
            'photo' => '',
            'admin' => '3',
            'remember_token' => 'E2bnQqCEUFIBA4izVXiuOTGhN2fkqcQ8c198OePZTKj7kKmHbS7puRDIRHMO',
            'created_at' => '2018-04-25 06:10:37',
            'updated_at' => '2018-04-25 06:10:37',
            'firstname' => '',
            'lastname' => '',
            'dob' => '',
            'address_id' => '0',
            'sia_licence' => '',
            'sia_expirydate' => '',
            'work_category' => '0',
            'nation_id' => '0',
            'visa_no' => '',
            'niutr_no' => '',
            'pass_page' => '',
            'visa_page' => '',
            'sia_doc' => '',
            'address_proof' => '',
            'passphoto' => '',
            'phone_verified' => '0',
            'added' => '0.00',
            'spent' => '0.00'
        ]);
    }
}
