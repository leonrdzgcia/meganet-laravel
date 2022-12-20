<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Package::insert([
            [
//                1
                'name' => 'bootstrap-multiselect',
                'url' => '/plugins/bootstrap-multiselect/css/bootstrap-multiselect.min.css',
                'type' => 'css',
            ],
            [
//                2
                'name' => 'bootstrap-multiselect',
                'url' => '/plugins/bootstrap-multiselect/js/bootstrap-multiselect.min.js',
                'type' => 'js',
            ],
            [
//                3
                'name' => 'toaster',
                'url' => '/plugins/toastr/build/toastr.min.css',
                'type' => 'css'
            ],
            [
//                4
                'name' => 'select2',
                'url' => '/plugins/select2/css/select2.min.css',
                'type' => 'css',
            ],
            [
//                5
                'name' => 'select2',
                'url' => '/plugins/select2/js/select2.min.js',
                'type' => 'js',
            ],
            [
//                6
                'name' => 'ckeditor',
                'url' => '/assets/libs/ckeditor/ckeditor.min.js',
                'type' => 'js',
            ],
            [
//                7
                'name' => 'datatables.net-bs4',
                'url' => '/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css',
                'type' => 'css',
            ],
            [
//                8
                'name' => 'datatables.net-buttons-bs4',
                'url' => '/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css',
                'type' => 'css',
            ],
            [
//                9
                'name' => 'datatables.net-responsive-bs4',
                'url' => '/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css',
                'type' => 'css',
            ],
            [
//                10
                'name' => 'datatables.net',
                'url' => '/assets/libs/datatables.net/js/jquery.dataTables.min.js',
                'type' => 'js',
            ],
            [
//                11
                'name' => 'datatables.net-bs4',
                'url' => '/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
                'type' => 'js',
            ],
            [
//                12
                'name' => 'datatables.net-buttons',
                'url' => '/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js',
                'type' => 'js',
            ],
            [
//                13
                'name' => 'datatables.net-buttons-bs4',
                'url' => '/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js',
                'type' => 'js',
            ],
            [
//                14
                'name' => 'jszip',
                'url' => '/assets/libs/jszip/jszip.min.js',
                'type' => 'js',
            ],
            [
//                15
                'name' => 'pdfmake',
                'url' => '/assets/libs/pdfmake/build/pdfmake.min.js',
                'type' => 'js',
            ],
            [
//                16
                'name' => 'pdfmake',
                'url' => '/assets/libs/pdfmake/build/vfs_fonts.js',
                'type' => 'js',
            ],
            [
//                17
                'name' => 'datatables.net-buttons',
                'url' => '/assets/libs/datatables.net-buttons/js/buttons.html5.min.js',
                'type' => 'js',
            ],
            [
//                18
                'name' => 'datatables.net-buttons',
                'url' => '/assets/libs/datatables.net-buttons/js/buttons.print.min.js',
                'type' => 'js',
            ],
            [
//                19
                'name' => 'datatables.net-buttons',
                'url' => '/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js',
                'type' => 'js',
            ],
            [
//                20
                'name' => 'apexcharts',
                'url' => '/assets/libs/apexcharts/apexcharts.min.js',
                'type' => 'js',
            ],
            [
//                21
                'name' => 'choice',
                'url' => 'assets/libs/choices.js/public/assets/styles/choices.min.css',
                'type' => 'css'
            ],
            [
//                22
                'name' => 'choice',
                'url' => '/assets/libs/choices.js/public/assets/scripts/choices.min.js',
                'type' => 'js'
            ],
            [
//                23
                'name' => 'ckeditor',
                'url' => '/assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js',
                'type' => 'js'
            ],
        ]);
    }
}
