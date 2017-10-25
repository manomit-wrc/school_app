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
        $user = new \App\User();
        $user->first_name = 'Manomit';
        $user->last_name = 'Mitra';
        $user->email = 'manomit@wrctpl.com';
        $user->password = bcrypt('sobhan123');
        $user->role_code = 'SITEADM';

        $user->save();
    }
}
