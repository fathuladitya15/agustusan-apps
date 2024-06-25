<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //  // Membuat roles
        //  $roles = ['admin', 'employee'];
        //  foreach ($roles as $role) {
        //      Role::create(['name' => $role]);
        //  }
        //  // Membuat permissions
        // $permissions = ['read-profile', 'create-employee','update-employee','delete-employee'];
        // foreach ($permissions as $permission) {
        //     Permission::create(['name' => $permission]);
        // }

        // $adminRole = Role::where('name', 'admin')->first();
        // $adminRole->syncPermissions($permissions);


        // $admin->assignRole('admin');

        // Membuat roles
        $adminRole = Role::create(['name' => 'admin']);
        $pengurusRTRole = Role::create(['name' => 'pengurus_rt']);
        $karangTarunaRole = Role::create(['name' => 'karang_taruna']);
        $wargaRole = Role::create(['name' => 'warga']);

        // Membuat permissions
        $manageUsersPermission = Permission::create(['name' => 'manage_users']); //Kelola pengguna
        $manageDocumentsPermission = Permission::create(['name' => 'manage_documents']); // kelola dokumen
        $manageAnnouncementsPermission = Permission::create(['name' => 'manage_announcements']); //kelola pengumuman
        $manageRoleAndPermission = Permission::create(['name' => 'manage_users_permission']); // kelola roles dan permission
        $manageHousehold = Permission::create(['name' => 'manage_household']);
        $manageEvent = Permission::create(['name' => 'manage_event']);
        // Tambahkan permissions lainnya sesuai kebutuhan

        // Assign permissions ke roles
        $adminRole->syncPermissions([$manageUsersPermission, $manageDocumentsPermission, $manageAnnouncementsPermission,$manageRoleAndPermission,$manageEvent]);
        $pengurusRTRole->syncPermissions([$manageDocumentsPermission, $manageAnnouncementsPermission]);
        $karangTarunaRole->syncPermissions([$manageAnnouncementsPermission,$manageHousehold,$manageEvent]);
        // Tambahkan assignments lainnya sesuai kebutuhan

        // Membuat user admin
        $admin = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Assign roles ke user
        $admin->assignRole($adminRole);

        // Membuat user admin
        $perngurusRT = User::create([
            'name' => 'pengurus RT',
            'username' => 'pengurus',
            'email' => 'perngurus@example.com',
            'password' => Hash::make('password'),
        ]);

        // Assign roles ke user
        $perngurusRT->assignRole($pengurusRTRole);

        // Membuat user admin
        $karangTaruna = User::create([
            'name' => 'Karang Taruna',
            'username' => 'karang_taruna',
            'email' => 'karangTaruna@example.com',
            'password' => Hash::make('password'),
        ]);

        // Assign roles ke user
        $karangTaruna->assignRole($karangTarunaRole);

        // Tambahkan assignments role ke user lainnya

        $resky = User::create([
            'name' => 'Resky almiraj',
            'username' => 'resky',
            'email' => 'karangTarunaa@example.com',
            'password' => Hash::make('password'),
        ]);

        $resky->assignRole($karangTarunaRole);

    }
}
