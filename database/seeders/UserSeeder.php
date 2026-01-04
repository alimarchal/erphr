<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@erphr.test',
            'password' => Hash::make('password'),
            'is_super_admin' => 'Yes',
            'is_active' => 'Yes',
        ]);
        $superAdmin->assignRole('super-admin');

        // Create 9 more users
        $names = [
            'Ali Raza', 'John Doe', 'Jane Smith', 'Robert Wilson',
            'Sarah Jenkins', 'Michael Brown', 'Emily Davis', 'David Miller', 'Lisa Anderson',
        ];

        foreach ($names as $index => $name) {
            $user = User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)).'@erphr.test',
                'password' => Hash::make('password'),
                'is_super_admin' => 'No',
                'is_active' => 'Yes',
            ]);

            if ($index < 2) {
                $user->assignRole('admin');
            } else {
                $user->assignRole('user');
            }
        }
    }
}
