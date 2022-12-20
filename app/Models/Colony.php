<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colony extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function municipio(){
        return $this->belongsTo(Municipality::class, 'municipality_id');
    }
}
