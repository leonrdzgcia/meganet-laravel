<?php

namespace App\Models;

use App\Http\Controllers\FileController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentClient extends Model
{
    use HasFactory;
    protected $guarded = [];

    
    const FILE_FIELDS = [
        'file' => 'file'
    ];

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function scopeFilters($query, $columns, $search = null)
    {
        if (isset($search)) {
            return $query->where(function ($query) use ($search, $columns) {
                foreach (collect($columns)->filter(function ($value) {
                    return $value != 'action';
                })->toArray() as $value) {
                    $query->orWhere($value, 'like', '%' . $search . '%');
                }
            });
        }
    }

    public function updateDocumentUpload($file){
        if ($file){
            $this->file()->first()->delete();

            $file_process = new FileController;
            $module_path = 'cliente/'. $this->client->id .'/document';
            $properties = $file_process->processSingleFileAndReturnProperties($file, $module_path, $this->id);

            $this->file()->create($properties);
            $file->storeAs('/public/'. $module_path .'/'. $this->id, $properties['name']);
        }

        return $this;
    }
}
