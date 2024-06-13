<?php

namespace Database\Seeders;

use App\Models\Manager\Config;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            [
                'key' => 'config_emp_attempt',
                'value' => '1',
            ],
            [
                'key' => 'make_emp_attempt',
                'value' => '0',
            ],
            [
                'key' => 'config_att_attempt',
                'value' => '1',
            ],
            [
                'key' => 'make_att_attempt',
                'value' => '0',
            ],
            // Profile Service
            [
                'key' => 'config_profile_attempt',
                'value' => '1',
            ],
            [
                'key' => 'make_profile_attempt',
                'value' => '1',
            ],



        ];
        foreach ($groups as $group){
            Config::create($group);
        }
    }
}
