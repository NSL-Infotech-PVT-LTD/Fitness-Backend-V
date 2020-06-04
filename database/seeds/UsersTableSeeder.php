<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [
            'name' => 'admin',
            'email' => 'admin@volt.com',
            'password' => Hash::make('12345678'),
                // 'phone' => '98166422',
        ];

        \App\AdminUser::create($data);
    }

}
