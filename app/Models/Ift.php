<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ift extends Model
{
    use HasFactory;
    protected $guarded = [];

        /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

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
