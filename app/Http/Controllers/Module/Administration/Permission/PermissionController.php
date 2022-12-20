<?php

namespace App\Http\Controllers\Module\Administration\Permission;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{

    public function __construct()
    {
    }

    public function get($role_id)
    {
        $role = Role::find($role_id);
        $permissions = $role->permissions()->pluck('name','name');

        $module = Module::where('name', 'Permission')->first();
        $fields = $module->getfields();

        foreach ($fields as $key => $val){
            if (isset($permissions[$key])){
                $fields[$key]['value'] = true;
                $has_inputs_depend = isset($fields[$key]['inputs_depend']);

                if ($has_inputs_depend){
                    foreach ($fields[$key]['inputs_depend'] as $keyInput => $valueInput){
                        if (isset($permissions[$keyInput])) $fields[$key]['inputs_depend']->$keyInput->value = true;
                    }
                }
            }
        }
        return $fields;
    }

    public function hasPermissionToView($view)
    {
        $view_permission = config('view_permission');
        $permissions = $this->getPermissionForUserAuthenticated();

        $has_permission = collect($view_permission)
            ->filter(function ($value, $key) use ($permissions, $view){
                $has_permission = false;
                foreach ($value as $v){
                    if ($view === $v) $has_permission = true;
                }
                return isset($permissions[$key]) && $has_permission;
            });
        if (count($has_permission) || $this->userAutenticated()->isAdmin()) return [
            'data' => true
        ];
        return [
            'data' => false
        ];
    }

    public function allViewHasPermission(){
        $view_permission = config('view_permission');
        if ($this->userAutenticated()->isAdmin()) return collect(['super-administrator' => 'super-administrator']);

        $permissions = $this->getPermissionForUserAuthenticated();
        return collect($view_permission)->intersectByKeys($permissions->toArray())->flatten();
    }
}
