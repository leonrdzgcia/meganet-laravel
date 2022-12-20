<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'login_user',
        'password',
        'father_last_name',
        'mother_last_name',
        'phone',
        'location',


    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['rol_name'];

    public function system_user()
    {
        return $this->hasOne(SystemUser::class);
    }

    public function perfil()
    {
        return $this->hasOne(Perfil::class);
    }

   
    public function user_column_datatable_module()
    {
        return $this->hasMany(UserColumnDatatableModule::class);
    }
  

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->toFormattedDateString();
    }

    public function getRolNameAttribute()
    {
        return implode(", ", $this->getRoleNames()->toArray());
    }

    public function isAdmin()
    {
        return in_array('super-administrator', $this->getRoleNames()->toArray());
    }

}
