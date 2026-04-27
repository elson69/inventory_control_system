<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@inventory.test'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        $supplierUsers = [
            ['name' => 'Alpha Supplies Co.',   'email' => 'supplier1@inventory.test'],
            ['name' => 'Beta Tech Distributors', 'email' => 'supplier2@inventory.test'],
            ['name' => 'Gamma Office Solutions', 'email' => 'supplier3@inventory.test'],
        ];

        foreach ($supplierUsers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->assignRole('supplier');
        }
    }
}
