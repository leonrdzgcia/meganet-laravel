<?php

namespace App\Console\Commands;

use App\Http\Traits\RouterConnection;
use App\Models\Router;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MikrotikBackupCommand extends Command
{
    use RouterConnection;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mikrotikBackup:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Mikrotik backup daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $routers = Router::all();
        foreach ($routers as $router){
            $device_ip = $router->ip_host;
            $mikrotik = $router->mikrotik()->first();
            $connection = $this->initConnection($mikrotik, $device_ip);

            $file = $this->fileExport($connection);
            $name =  'backup/'. \Carbon\Carbon::now()->format('y-m').'/'. $router->id.'-'. \Carbon\Carbon::now()->format('d').'.rsc';
            Storage::disk('local')->put($name, $file);
        }
    }
}
