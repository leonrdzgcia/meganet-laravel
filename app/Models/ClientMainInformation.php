<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientMainInformation extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $primaryKey = 'client_id';
    protected $appends = ['client_name_with_fathers_names'];


    /**
     * Type of billings to int
     * @param $value
     * @return int
     */

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function colony()
    {
        return $this->belongsTo(Colony::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function type_billing()
    {
        return $this->belongsTo(TypeBilling::class, 'type_of_billing_id');
    }

    public function setTypeOfBillingAttribute($value)
    {
        $this->attributes['type_of_billing'] = intval($value);
    }

    public function getClientNameWithFathersNamesAttribute()
    {
        return $this->name .
            ' ' .
            $this->father_last_name .
            ' ' .
            $this->mother_last_name;
    }

    public function setDischargeDateAttribute($value)
    {
        $this->attributes['discharge_date'] = Carbon::now()->toDateTimeString();
    }

    public function getDischargeDateAttribute($value)
    {
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d\TH:i');
        } catch (\Exception $e) {
            return Carbon::createFromFormat('d/m/Y', $value)->format(
                'Y-m-d\TH:i'
            );
        }
    }

    public function getStateName()
    {
        $stateName = $this->state()->first();
        if ($stateName) {
            return $this->state()->first()->name;
        }
        return '';
    }

    public function getColonyName()
    {
        $colonyName = $this->colony()->first();
        if ($colonyName) {
            return $this->colony()->first()->name;
        }
        return '';
    }

    public function getMunicipalityName()
    {
        $municipalityName = $this->municipality()->first();
        if ($municipalityName) {
            return $this->municipality()->first()->name;
        }
        return '';
    }

}
