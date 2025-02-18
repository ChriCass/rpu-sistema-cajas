<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\DolarService;

class Dolares extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:dolares';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando jala los dolares de sunat';

    protected $dolarService;

    /**
     * Create a new command instance.
     */
    public function __construct(DolarService $dolarService)
    {
        parent::__construct();
        $this->dolarService = $dolarService;
    }


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tipoCambio = $this->dolarService->ObtenerDolar();
    }
}
