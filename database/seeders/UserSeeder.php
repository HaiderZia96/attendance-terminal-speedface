<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     // App User for app auto entries
        $AppUsers = [
            [
                'name' => 'app',
                'email' => 'app@app.com',
                'password'=>bcrypt('OZBvdKViiLSqodfn76'),
                'status'  => 1,
                'module_id'  => 1,
                'email_verified_at'  => Carbon::now()->toDateTimeString(),
            ]
        ];
        foreach ($AppUsers as $user){
            $user = User::create($user);
        }

        // Super Admin User
        $SuperAdminUsers = [
            [
                'name' => 'super-admin',
                'email' => 'web@tuf.edu.pk',
                'password'=>bcrypt('OZBvdKViiLSq64xF'),
                'status'  => 1,
                'module_id'  => 1,
                'email_verified_at'  => Carbon::now()->toDateTimeString(),
            ]
        ];
        foreach ($SuperAdminUsers as $user){
            $user = User::create($user);
            $user->assignRole(Role::all());
        }


    }
}
