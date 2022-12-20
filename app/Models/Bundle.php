<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    use HasFactory;
    protected $guarded = [];

    const MULTIPLE_RELATIONS = [
        'partners' => 'partners',
        'types_of_billing' => 'billings'
    ];

    const RELATIONS_MULTIPLE_WITH_CANT = [
        'planes_internet' => 'planes_internet',
        'planes_voz' => 'planes_voz',
        'planes_custom' => 'planes_custom'
    ];

    const SINGLE_RELATIONS = [
        'BundleLeft' => 'Bundle_Left',
        'BundleRight' => 'Bundle_Right',
    ];

    public function partners(){
        return $this->morphToMany(
            Partner::class,
            'partner_module',
            'partner_module'
        )->withTimestamps();
    }

    public function billings(){
        return $this->morphToMany(
            TypeBilling::class,
            'plan_billing',
            'plan_type_billings'
        )->withTimestamps();
    }

    public function planes_internet()
    {
        return $this->morphedByMany(
            Internet::class,
            'plan_bundle',
            'plan_bundles'
        )->withPivot('cant');
    }

    public function planes_voz()
    {
        return $this->morphedByMany(Voise::class, 'plan_bundle','plan_bundles')->withPivot('cant');
    }

    public function planes_custom()
    {
        return $this->morphedByMany(Custom::class, 'plan_bundle','plan_bundles')->withPivot('cant');
    }

    public function client_bundle_services(){
        return $this->hasMany('App\Models\ClientBundleService');
    }

    public function scopeFilters($query, $columns, $search = null)
    {
        if (isset($search)){
            return $query->where(function ($query) use ($search, $columns){
                foreach (collect($columns)->filter(function ($value){
                    return $value != 'action';
                })->toArray() as $value){
                    $query->orWhere($value,'like','%'.$search.'%');
                }
            });
        }
    }
}
