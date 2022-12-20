<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $columns = collect(config('module.administration.permission.constants.Permission.FIELDS'))->keys();
        $group_permission = ['dashboard', 'plan', 'crm', 'client','router'];
        $permissions = [];
        foreach ($group_permission as $group){
            $permissions[$group] = $columns->filter(function ($value, $key) use ($group){
                return Str::startsWith($value, $group);
            });

            foreach ($permissions[$group] as $permission){
                Permission::create([
                    'name' => $permission
                ]);
            }
        }
    }
}
