<?php

use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $data = [
            'terms_and_conditions' => 'terms and conditions',
            'privacy_policy' => 'privacy policy',
            'about_us' => 'about us',
            'admin_email' => 'contact@volt.com',
        ];
        App\Configuration::create($data);
    }

}
