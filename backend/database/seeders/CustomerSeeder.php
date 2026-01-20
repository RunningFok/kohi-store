<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Hans Müller',
                'email' => 'hans.mueller@example.com',
                'password' => 'password',
                'phone' => '+491234567890',
                'address' => 'Hauptstraße 123',
                'city' => 'Berlin',
                'postal_code' => '10115',
                'country' => 'Deutschland',
            ],
            [
                'name' => 'Anna Schmidt',
                'email' => 'anna.schmidt@example.com',
                'password' => 'password',
                'phone' => '+491987654321',
                'address' => 'Bahnhofstraße 45',
                'city' => 'München',
                'postal_code' => '80331',
                'country' => 'Deutschland',
            ],
            [
                'name' => 'Thomas Fischer',
                'email' => 'thomas.fischer@example.com',
                'password' => 'password',
                'phone' => '+491555123456',
                'address' => 'Kirchgasse 78',
                'city' => 'Hamburg',
                'postal_code' => '20095',
                'country' => 'Deutschland',
            ],
            [
                'name' => 'Maria Weber',
                'email' => 'maria.weber@example.com',
                'password' => 'password',
                'phone' => '+491444555666',
                'address' => 'Lindenstraße 32',
                'city' => 'Köln',
                'postal_code' => '50667',
                'country' => 'Deutschland',
            ],
            [
                'name' => 'Peter Wagner',
                'email' => 'peter.wagner@example.com',
                'password' => 'password',
                'phone' => '+491777888999',
                'address' => 'Mühlenweg 65',
                'city' => 'Frankfurt',
                'postal_code' => '60311',
                'country' => 'Deutschland',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
