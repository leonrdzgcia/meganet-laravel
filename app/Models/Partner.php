<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function internet()
    {
        return $this->morphedByMany(Internet::class, 'partner_module', 'partner_module');
    }

    public function voz()
    {
        return $this->morphedByMany(Voise::class, 'partner_module', 'partner_module');
    }

    public function router()
    {
        return $this->morphedByMany(Router::class, 'partner_module', 'partner_module');
    }
}
