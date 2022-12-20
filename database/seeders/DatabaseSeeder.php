<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(\Database\Seeders\TypeBillingSeeder::class);
        $this->call(\Database\Seeders\TaxSeeder::class);
        $this->call(\Database\Seeders\PartnerSeeder::class);

        $this->call(\Database\Seeders\PackageSeeder::class);
        $this->call(\Database\Seeders\ModuleSeeder::class);
        $this->call(\Database\Seeders\PermissionSeeder::class);

        $this->call(\Database\Seeders\StateSeeder::class);
        $this->call(\Database\Seeders\MunicipalitySeeder::class);
        $this->call(\Database\Seeders\ColonySeeder::class);

        // Admin User
        $admin = \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'login_user' => 'admin',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        // User
        $user = \App\Models\User::create([
            'name' => 'User',
            'login_user' => 'user',
            'email' => 'user@user.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        $role = Role::create(['name' => 'super-administrator']);
        $admin->assignRole('super-administrator');
        $role->givePermissionTo(Permission::all()->pluck('id')->toArray());
        $role = Role::create(['name' => 'User']);
        $user->assignRole('User');
        $role->givePermissionTo([5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24]);



        $this->call(\Database\Seeders\InternetSeeder::class);
        $this->call(\Database\Seeders\VoiseSeeder::class);
        $this->call(\Database\Seeders\CustomSeeder::class);
        $this->call(\Database\Seeders\BundleSeeder::class);
        // $this->call(\Database\Seeders\TicketSeeder::class);
        // $this->call(\Database\Seeders\CrmSeeder::class);
        $this->call(\Database\Seeders\RouterSeeder::class);
        // $this->call(\Database\Seeders\ClientSeeder::class);
        $this->call(\Database\Seeders\NetworkSeeder::class);
    }
}
