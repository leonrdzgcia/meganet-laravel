<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientInvoiceService extends Model
{
    use HasFactory;
    protected $table = 'client_serviceables';
    protected $guarded = [];
}
