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
        // 会社作成
        Company::factory(3)->create();

        // ユーザー作成
        User::factory(1)->create(['email' => 'test1@test.com', 'password' => 'password', 'company_id' => 1]);
        User::factory(1)->create(['email' => 'test2@test.com', 'password' => 'password', 'company_id' => 2]);
        User::factory(1)->create(['email' => 'test3@test.com', 'password' => 'password', 'company_id' => 3]);

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

        // スーパー管理者ユーザー
        User::create([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        // パーミッション
        Permission::updateOrCreate(['name' => 'company.show']);
        Permission::updateOrCreate(['name' => 'company.edit']);

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
        User::find(1)->assignRole($c1_admin_role); // 会社1の管理者
        setPermissionsTeamId(2);
        User::find(1)->assignRole($c2_member_role); // 会社2のメンバー

        setPermissionsTeamId(2);
        User::find(2)->assignRole($c2_admin_role); // 会社2の管理者

        setPermissionsTeamId(3);
        User::find(3)->assignRole($c3_admin_role); // 会社3の管理者

        // スーパー管理者ユーザーの設定
        // 全会社の管理者ユーザーにロールを付与
        $admin = User::find(4);
        foreach (Company::all() as $company) {
            setPermissionsTeamId($company->id);
            $role = Role::where(['name' => 'company_admin', 'team_id' => $company->id])->first();
            $admin->assignRole($role);
            $admin->companies()->attach($company->id);
        }

        // スーパー管理者だけが持つ権限
        setPermissionsTeamId(null);
        $permission = Permission::updateOrCreate(['name' => 'super_admin']);
        $super_role = Role::updateOrCreate(['name' => 'super_admin', 'team_id' => null], ['guard_name' => 'web']);
        $super_role->givePermissionTo($permission);
        $admin->assignRole($super_role);
    }
}
