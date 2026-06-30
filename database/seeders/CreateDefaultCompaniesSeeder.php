<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateDefaultCompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groupId = \App\Models\Group::firstOrCreate(['name' => 'Casa'])->id;
        $companies = [
            [
                'name' => 'AS ATELIE MODA FEMININA LTDA',
                'cnpj' => '41.896.144/0001-55',
                'group_id' => $groupId
            ],
            [
                'name' => 'AMANDA MARIA PEREIRA SANTANA',
                'cnpj' => '37.536.320/0001-70',
                'group_id' => $groupId
            ]
        ];

        foreach ($companies as $company) {
            \App\Models\Company::create($company);
        }
    }
}
