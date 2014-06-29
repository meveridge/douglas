<?php

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        User::create(array('username' => 'rshackleford',
                'id' => '5412419',
                'fullname' => 'Rusty Shackleford',
                'email' => 'rshackleford@example.coop',
                'password' => Hash::make('my_pass'),
                'isAdmin' => '0'));
        User::create(array('username' => 'bstrickland',
                'id' => '3643733',
                'fullname' => 'Buck Strickland',
                'email' => 'bstrickland@example.coop',
                'password' => Hash::make('sup_pass'),
                'isAdmin' => '1'));
        }
}
