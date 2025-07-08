<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleSheetsService;

class ExportRipsToSheets extends Command
{
    protected $signature = 'rips:export';
    protected $description = 'Exporta datos RIPS a Google Sheets';

    public function handle()
    {
        $this->info('Iniciando exportación a Google Sheets...');
        
        $service = new GoogleSheetsService();
        $result = $service->exportRipsDataToSheet();
        
        if ($result['success']) {
            $this->info('✅ ' . $result['message']);
            $this->info('Registros exportados: ' . $result['rows_exported']);
        } else {
            $this->error('❌ ' . $result['message']);
        }
    }
}