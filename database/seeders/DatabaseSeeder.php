<?php

namespace Database\Seeders;

use App\Models\Company;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\UserCompany;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $companies = Company::factory(3)->create();

        $companies->each(function ($company) {
            // 会社の最初のユーザを作成
            User::factory(1)->create([
                'email' => "test{$company->id}@test.com",
                'password' => 'password',
                'company_id' => $company->id,
            ]);
        });

        // 会社とユーザーの紐付け
        UserCompany::create([
            'user_id' => 1,
            'company_id' => 1,
        ]);
        UserCompany::create([
            'user_id' => 1,
            'company_id' => 2,
        ]);
        UserCompany::create([
            'user_id' => 2,
            'company_id' => 2,
        ]);
        UserCompany::create([
            'user_id' => 3,
            'company_id' => 3,
        ]);

        // パーミッション
        $permissions = [
            'company.show',
            'company.edit',
        ];
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }

        // 会社ごとロールを作成
        $c1_admin_role = Role::updateOrCreate(['name' => 'company_admin', 'team_id' => 1], ['guard_name' => 'web']);
        $c1_member_role = Role::updateOrCreate(['name' => 'company_member', 'team_id' => 1], ['guard_name' => 'web']);
        $c1_admin_role->givePermissionTo(['company.show', 'company.edit']);
        $c1_member_role->givePermissionTo(['company.show']);

        $c2_admin_role = Role::updateOrCreate(['name' => 'company_admin', 'team_id' => 2], ['guard_name' => 'web']);
        $c2_member_role = Role::updateOrCreate(['name' => 'company_member', 'team_id' => 2], ['guard_name' => 'web']);
        $c2_admin_role->givePermissionTo(['company.show', 'company.edit']);
        $c2_member_role->givePermissionTo(['company.show']);

        $c3_admin_role = Role::updateOrCreate(['name' => 'company_admin', 'team_id' => 3], ['guard_name' => 'web']);
        $c3_member_role = Role::updateOrCreate(['name' => 'company_member', 'team_id' => 3], ['guard_name' => 'web']);
        $c3_admin_role->givePermissionTo(['company.show', 'company.edit']);
        $c3_member_role->givePermissionTo(['company.show']);


        // ユーザにロールを付与
        setPermissionsTeamId(1);
        User::find(1)->assignRole($c1_admin_role);

        setPermissionsTeamId(2);
        User::find(1)->assignRole($c2_member_role);
        User::find(2)->assignRole($c2_admin_role);

        setPermissionsTeamId(3);
        User::find(3)->assignRole($c3_admin_role);


    }
}
