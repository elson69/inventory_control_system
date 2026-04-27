<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $supplierData = [
            [
                'email' => 'supplier1@inventory.test',
                'company_name' => 'Alpha Supplies Co.',
                'contact_name' => 'Alice Reyes',
                'phone' => '+63-912-001-0001',
                'address' => '123 Rizal Ave, Manila, Metro Manila',
            ],
            [
                'email' => 'supplier2@inventory.test',
                'company_name' => 'Beta Tech Distributors',
                'contact_name' => 'Bob Santos',
                'phone' => '+63-922-002-0002',
                'address' => '456 Mabini St, Cebu City, Cebu',
            ],
            [
                'email' => 'supplier3@inventory.test',
                'company_name' => 'Gamma Office Solutions',
                'contact_name' => 'Carmen Dela Cruz',
                'phone' => '+63-932-003-0003',
                'address' => '789 Bonifacio Blvd, Davao City, Davao del Sur',
            ],
        ];

        foreach ($supplierData as $data) {
            $user = User::where('email', $data['email'])->first();
            if ($user) {
                Supplier::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'company_name' => $data['company_name'],
                        'contact_name' => $data['contact_name'],
                        'phone' => $data['phone'],
                        'address' => $data['address'],
                    ]
                );
            }
        }
    }
}
