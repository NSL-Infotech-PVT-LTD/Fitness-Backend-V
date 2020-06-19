<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        DB::table('permissions')->insert([
            [
                'name' => 'Group Class',
                'label' => 'group-class',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'Personal-Trainer',
                'label' => 'personal-trainer',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]
        ]);

        $roles = ['Gym Members' => [
                'single' => [
                    'monthly' => '600'
                    , 'quarterly' => '1700'
                    , 'half_yearly' => '3200'
                    , 'yearly' => '6000'
                ],
                'couple' => [
                    'monthly' => '1100'
                    , 'quarterly' => '3100'
                    , 'half_yearly' => '5800'
                    , 'yearly' => '10800'
                ],
                'family_with_2' => [
                    'monthly' => '1600'
                    , 'quarterly' => '4300'
                    , 'half_yearly' => '8200'
                    , 'yearly' => '15300'
                ],
            ], 'Pool and beach Members' => [
                'single' => [
                    'monthly' => '700'
                    , 'quarterly' => '1500'
                    , 'half_yearly' => '2400'
                    , 'yearly' => '36000'
                ],
                'couple' => [
                    'monthly' => '1500'
                    , 'quarterly' => '2900'
                    , 'half_yearly' => '4800'
                    , 'yearly' => '7000'
                ],
                'family_with_2' => [
                    'monthly' => '1700'
                    , 'quarterly' => '3400'
                    , 'half_yearly' => '7200'
                    , 'yearly' => '10600'
                ],
                'child' => [
                    'monthly' => '400'
                    , 'quarterly' => '700'
                    , 'half_yearly' => '1300'
                    , 'yearly' => '1900'
                ],
        ]];
        foreach ($roles as $name => $categories):
            foreach ($categories as $category => $plans):
                $role = App\Role::create(['name' => $name, 'label' => $name . $category, 'category' => $category, 'created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()]);
                $rolePlan = [];
                foreach ($plans as $feetype => $fee):
                    $rolePlan[] = ['fee_type' => $feetype, 'fee' => $fee, 'role_id' => $role->id];
                endforeach;
                \App\RolePlans::insert($rolePlan);
            endforeach;
        endforeach;

        DB::table('roles')->insert([
            [
                'name' => 'Local Guest',
                'label' => 'guest',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
            [
                'name' => 'Fairmont Hotel Guest',
                'label' => 'fairmont-hotel-guest',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ],
        ]);
    }

}
