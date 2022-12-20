<?php

namespace App\Models;

use App\Http\Controllers\FileController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];

    const FILE_FIELDS = [
        'file' => 'file'
    ];

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function payment_promise()
    {
        return $this->hasMany(PaymentPromise::class);
    }

    public function payment_method(){
        return $this->belongsTo(MethodOfPayment::class,'payment_method_id');
    }

    public function uploadFile($file)
    {
        if ($this->isModelClient()){
            $file_process = new FileController;
            $module_path = 'client/'. $this->paymentable_id .'/payment';
            $properties = $file_process->processSingleFileAndReturnProperties($file, $module_path, $this->id);

            $this->file()->create($properties);
            $file->storeAs('/public/'. $module_path .'/'. $this->id, $properties['name']);
        }
        return $this;
    }

    public function updateFileUpload($file){
        if ($file){
            $this->file()->first()->delete();

            $file_process = new FileController;
            $module_path = 'client/'. $this->paymentable_id .'/payment';
            $properties = $file_process->processSingleFileAndReturnProperties($file, $module_path, $this->id);

            $this->file()->create($properties);
            $file->storeAs('/public/'. $module_path .'/'. $this->id, $properties['name']);
        }

        return $this;
    }

    public function isModelClient(){
        return $this->paymentable_type == 'App\Models\Client';
    }

    public function getPaymentMethod(){
        return $this->payment_method->type;
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
