<?php

namespace Database\Seeders;

use App\Models\Company;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $companies = Company::factory(3)->create();

        $companies->each(function ($company) {
            // 1つの会社に1人のユーザーを紐付ける
            $company->users()->saveMany(User::factory(1)->make([
                'email' => "test{$company->id}@test.com",
                'password' => 'password',
                'company_id' => $company->id,
            ]));
        });

        // ID1のユーザを会社2に紐づける
        UserCompany::create([
            'user_id' => 1,
            'company_id' => 2,
        ]);
    }
}
