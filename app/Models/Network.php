<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function network_ip()
    {
        return $this->hasMany(NetworkIp::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function scopeFilters($query, $columns, $search = null)
    {
        if (isset($search)){
            return $query->where(function ($query) use ($search, $columns){
                foreach ($columns as $value){
                    $query->orWhere($value,'like','%'.$search.'%');
                }
            });
        }
    }
}
