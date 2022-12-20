<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Router extends Model
{
    use HasFactory;

    protected $guarded = [];

    const MULTIPLE_RELATIONS = [
        'partners' => 'partners',
    ];

    const SINGLE_RELATIONS = [
        'Mikrotik' => [
            'relation_name' => 'mikrotik',
            'relation_field' => 'router_id',
            'local_relation' => 'id',
        ],
        'MikrotikConfig' => [
            'relation_name' => 'mikrotikconfig',
            'relation_field' => 'router_id',
            'local_relation' => 'id',
        ],
    ];

    public function clientinternetservices()
    {
        return $this->hasMany('App\Models\ClientInternetService');
    }

    public function mikrotik()
    {
        return $this->hasOne('App\Models\Mikrotik');
    }

    public function mikrotikconfig()
    {
        return $this->hasOne('App\Models\MikrotikConfig');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function partners()
    {
        return $this->morphToMany(
            Partner::class,
            'partner_module',
            'partner_module'
        )->withTimestamps();
    }

    public function scopeFilters($query, $columns, $search = null)
    {
        if (isset($search)) {
            return $query->where(function ($query) use ($search, $columns) {
                foreach (
                    collect($columns)
                        ->filter(function ($value) {
                            return $value != 'action';
                        })
                        ->toArray()
                    as $value
                ) {
                    $query->orWhere($value, 'like', '%' . $search . '%');
                }
            });
        }
    }

    public function isMikrotik()
    {
        return $this->type_of_nas === 'Mikrotik';
    }

    public function hasMikrotik()
    {
        return $this->mikrotik;
    }
}
