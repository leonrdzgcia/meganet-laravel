<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Module;

class PopulateFinanceTransactionToColumnDatatableModules extends Migration
{

        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            $bootstrap_multiselect = [1, 2];
            $select2 = [4, 5];
            $chosen_select = [21, 22];
            $toaster = [3];
            $datatables_packages = [7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];
            $ckeditor = [23];
            $bootstrap_multiselect_toaster_datatables_packages = \Illuminate\Support\Arr::collapse([$bootstrap_multiselect, $toaster, $datatables_packages, $select2, $chosen_select, $ckeditor]);
    
            
            $module = Module::create(['name' => 'FinanceTransaction']);
            $module->packages()->attach($bootstrap_multiselect_toaster_datatables_packages);
            $module->fields()->delete();
            $module->columnsDatatable()->insert($this->transformColumnsDatatableModel('module.finance.transaction.constants.', $module));
        }
    
        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            $module = Module::where('name', 'FinanceTransaction')->first();
            $module->packages()->detach();
            $module->columnsDatatable()->delete();
            $module->fields()->delete();
            $module->delete();
        }
    
        public function transformColumnsDatatableModel($dir, $module)
        {
            $values = [];
            $columns = config($dir . $module->name . '.DATATABLE_FIELDS');
            if (count($columns)) {
                foreach ($columns as $k => $v) {
                    $values[] = [
                        'module_id' => $module->id,
                        'name' => $k,
                        'label' => $v['name'],
                        'class' => $v['class'],
                    ];
                }
            }
            return $values;
        }
    }

