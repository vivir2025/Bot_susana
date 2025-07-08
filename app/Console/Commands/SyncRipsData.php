<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleSheetsService;

class SyncRipsData extends Command
{
    protected $signature = 'rips:sync 
                            {--import : Importar desde Sheets a la DB}
                            {--export : Exportar desde la DB a Sheets}
                            {--range= : Rango personalizado}';
    
    protected $description = 'Sincronización bidireccional de datos RIPS';

    public function handle()
    {
        $service = new GoogleSheetsService();
        $range = $this->option('range') ?? 'Factura!A1:Q100';
        
        if ($this->option('import')) {
            $this->info('Iniciando importación desde Google Sheets...');
            $result = $service->syncData('sheet-to-db', $range);
        } elseif ($this->option('export')) {
            $this->info('Iniciando exportación a Google Sheets...');
            $result = $service->syncData('db-to-sheet', $range);
        } else {
            $this->error('Debe especificar --import o --export');
            return;
        }
        
        if ($result['success']) {
            $this->info('✅ ' . $result['message']);
        } else {
            $this->error('❌ ' . $result['message']);
            
            if (isset($result['exception'])) {
                $this->error('Detalle: ' . $result['exception']->getMessage());
            }
        }
    }
}