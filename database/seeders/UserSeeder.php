<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Ms. Rahila Javed',
                'designation' => 'Divisional Head HR',
                'email' => 'dh_hrd@bankajk.com',
                'is_super_admin' => 'Yes',
                'role' => 'super-admin',
            ],
            [
                'name' => 'Mr. Asad Qadeer',
                'designation' => 'AVP/Senior Manager HR',
                'email' => 'sm_hrd@bankajk.com',
                'is_super_admin' => 'No',
                'role' => 'admin',
            ],
            [
                'name' => 'Ms. Sidra Nazir',
                'designation' => 'AVP/Manager HR Ops',
                'email' => 'manager.hroperations@bankajk.com',
                'is_super_admin' => 'No',
                'role' => 'user',
            ],
            [
                'name' => 'Kh. Abrar Ahmed',
                'designation' => 'OG-I/Manager HR Admin',
                'email' => 'manager_hrd@bankajk.com',
                'is_super_admin' => 'No',
                'role' => 'user',
            ],
            [
                'name' => 'Mr. Haseeb Khalid',
                'designation' => 'OG-II/Manager Training',
                'email' => 'manager.training@bankajk.com',
                'is_super_admin' => 'No',
                'role' => 'user',
            ],
            [
                'name' => 'Syeda Sehrish Naqvi',
                'designation' => 'OG-II/Officer',
                'email' => 'naqvisunnynaqvi@gmail.com',
                'is_super_admin' => 'No',
                'role' => 'user',
            ],
            [
                'name' => 'Ms. Asma Sana',
                'designation' => 'OG-II/MTO Officer',
                'email' => 'officersb_hrd@bankajk.com',
                'is_super_admin' => 'No',
                'role' => 'user',
            ],
            [
                'name' => 'Mr. Shoaib Zaib',
                'designation' => 'OG-III/Officer',
                'email' => 'shoaib.zaib@bankajk.com',
                'is_super_admin' => 'No',
                'role' => 'user',
            ],
            [
                'name' => 'Ms. Ishrat Batool',
                'designation' => 'OG-III/Officer',
                'email' => 'officeradmin_hrd@bankajk.com',
                'is_super_admin' => 'No',
                'role' => 'user',
            ],
            [
                'name' => 'Raja Saddam Ifraheem',
                'designation' => 'OG-III/Officer',
                'email' => 'hrd@bankajk.com',
                'is_super_admin' => 'No',
                'role' => 'user',
            ],
            [
                'name' => 'Ms. Nadia Ishfaq',
                'designation' => 'Management Internee',
                'email' => 'nadiaishfaq59@gmail.com',
                'is_super_admin' => 'No',
                'role' => 'user',
            ],
            [
                'name' => 'Ms. Maryum Ghulam Nabi',
                'designation' => 'Third Party',
                'email' => 'maryamabrar313@gmail.com',
                'is_super_admin' => 'No',
                'role' => 'user',
            ],
            [
                'name' => 'Ms. Sughra Bibi',
                'designation' => 'Third Party',
                'email' => 'sughrah667@gmail.com',
                'is_super_admin' => 'No',
                'role' => 'user',
            ],
            [
                'name' => 'Ms. Adeela Kanwal',
                'designation' => 'Third Party',
                'email' => 'adeela7228@gmail.com',
                'is_super_admin' => 'No',
                'role' => 'user',
            ],
            [
                'name' => 'Mr. Haris Ehsan',
                'designation' => 'Third Party',
                'email' => 'haris.ehsan@bankajk.com',
                'is_super_admin' => 'No',
                'role' => 'user',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'designation' => $userData['designation'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'is_super_admin' => $userData['is_super_admin'],
                'is_active' => 'Yes',
            ]);
            $user->assignRole($userData['role']);
        }
    }
}
