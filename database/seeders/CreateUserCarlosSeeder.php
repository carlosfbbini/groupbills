<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateUserCarlosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::firstOrCreate([
            'email' => 'carlosfbbini@hotmail.com'
        ], [
            'name' => 'Carlos',
            'password' => bcrypt('12345678'),
        ]);
    }
}
