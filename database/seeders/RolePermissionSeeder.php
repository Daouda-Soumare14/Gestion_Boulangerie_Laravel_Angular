<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'CLIENT']);
        Role::create(['name' => 'ADMIN']);
        Role::create(['name' => 'EMPLOYE']);

        // $user = User::find(1);
        // $user->assignRole('ADMIN');

        // $user = User::find(2);
        // $user->assignRole('EMPLOYE');

        // $user = User::find(3);
        // $user->assignRole('CLIENT');
    }
}
