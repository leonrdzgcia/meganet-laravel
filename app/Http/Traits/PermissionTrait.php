<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Auth;

trait PermissionTrait {
    public function getPermissionForUserAuthenticated()
    {
        return \App\Models\User::find(Auth::user()->id)
            ->getAllPermissions()
            ->pluck('id', 'name');
    }
}
