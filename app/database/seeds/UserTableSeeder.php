<?php

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        User::create(array('username' => 'rshackleford',
                'full name' => 'Rusty Shackleford',
                'email' => 'rshackleford@example.coop',
                'password' => Hash::make('my_pass'),
                'isAdmin' => '0'));
        User::create(array('username' => 'bstrickland',
                'full name' => 'Buck Strickland',
                'email' => 'bstrickland@example.coop',
                'password' => Hash::make('sup_pass'),
                'isAdmin' => '1'));
        }
}
