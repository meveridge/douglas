<?php

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('roles')->delete();

        User::create(array('name' => 'admin'));
        User::create(array('name' => 'regular'));
        }
}
