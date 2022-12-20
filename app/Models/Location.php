<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function router()
    {
        return $this->hasOne(Router::class);
    }

    public function network()
    {
        return $this->hasOne(Network::class);
    }
}
