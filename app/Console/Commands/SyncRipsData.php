<?php

namespace App\Console\Commands;

use App\Services\GoogleSheetsService;
use Illuminate\Console\Command;

class SyncRipsData extends Command
{
    protected $signature = 'rips:sync';
    protected $description = 'Sincroniza datos RIPS desde Google Sheets';

    public function handle()
    {
        $this->info('Iniciando sincronización de datos RIPS...');
        
        try {
            $googleSheets = new GoogleSheetsService();
            $googleSheets->syncRipsData();
            
            $this->info('✅ Datos sincronizados exitosamente!');
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
        }
    }
}