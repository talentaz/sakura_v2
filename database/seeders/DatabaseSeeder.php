<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            CustomerSeeder::class,
        ]);

        // Create admin user
        DB::table('users')->insert([
            'name'     => 'Admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('123456'),
            'role_id'  => 1, // Admin role
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create sample customer with new structure
        DB::table('customers')->insert([
            'name'       => 'John Doe',
            'email'      => 'customer@gmail.com',
            'country_id' => null, // Set to actual country ID when countries table exists
            'password'   => Hash::make('123456'),
            'status'     => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('rate')->insert([
            'rate'          => 110,
            'inspection'    => 300,
            'insurance'     => 100,
        ]);
        
        $country= config('config.country');
        foreach($country as $row){
            DB::table('ports')->insert([
                'country' => $row,
            ]); 
        }
    }
}

