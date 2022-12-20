<?php


namespace App\MyLibrary;


use App\Models\Module;
use Illuminate\Support\Facades\Log;

class Utility
{
    public static function modifyValueForCheckbox($input, $module)
    {
        $module = Module::where('name', $module)->with('fields')->first();
        $fields = $module->fields
            ->whereIn('type', ['input-checkbox', 'input-checkbox-with-inputs'])
            ->pluck('name', 'type')->toArray();
        foreach ($fields as $field) {
            $input[$field] = (isset($input[$field]) && $input[$field] == true);
        }

        return $input;
    }
}
