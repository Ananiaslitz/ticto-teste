<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedUsers();
        $this->seedPoints();
    }

    private function seedUsers(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'cpf' => '11122233344',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'birth_date' => Carbon::parse('1985-01-15')->format('Y-m-d'),
                'cep' => '01001-000',
                'street' => 'Rua Exemplo',
                'number' => '123',
                'city' => 'São Paulo',
                'address' => 'Avenida Exemplo, 456',
                'state' => 'SP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Employee User',
                'cpf' => '44455566677',
                'email' => 'employee@example.com',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'birth_date' => Carbon::parse('1995-06-25')->format('Y-m-d'),
                'cep' => '02002-000',
                'street' => 'Avenida Exemplo',
                'address' => 'Avenida Exemplo, 456',
                'number' => '456',
                'city' => 'Rio de Janeiro',
                'state' => 'RJ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    private function seedPoints(): void
    {
        DB::table('points')->insert([
            [
                'user_id' => 2, // Employee User ID
                'registered_at' => Carbon::now()->subMinutes(15),
                'latitude' => -23.550520,
                'longitude' => -46.633308,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2, // Employee User ID
                'registered_at' => Carbon::now()->subMinutes(30),
                'latitude' => -23.550521,
                'longitude' => -46.633309,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

}
