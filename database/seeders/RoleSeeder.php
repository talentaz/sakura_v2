<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'System Administrator'],
            ['name' => 'Sales Manager', 'slug' => 'sales_manager', 'description' => 'Sales Manager'],
            ['name' => 'Sales Agent', 'slug' => 'sales_agent', 'description' => 'Sales Agent'],
            ['name' => 'Shipment Officer', 'slug' => 'shipment_officer', 'description' => 'Shipment Officer'],
            ['name' => 'Purchaser', 'slug' => 'purchaser', 'description' => 'Purchaser'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}