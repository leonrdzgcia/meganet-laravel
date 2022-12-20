<?php

namespace App\Models;

use App\Http\Traits\Models\NetworkIp\NetworkIpTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkIp extends Model
{
    use HasFactory, NetworkIpTrait;

    protected $guarded = [];

    protected $appends = ['icon_for_host_category'];

    public function getIconForHostCategoryAttribute(){
        return $this->networkIpGetIconForHostCategory($this->host_category);
    }

    public function network(){
        return $this->belongsTo('App\Models\Network');
    }

    public function client(){
        return $this->belongsTo('App\Models\Client');
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
