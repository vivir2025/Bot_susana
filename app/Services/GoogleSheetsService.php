<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use App\Models\RipsData;
use Illuminate\Support\Facades\Log;

class GoogleSheetsService
{
    protected $client;
    protected $service;
    protected $spreadsheetId;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/google-sheets-credentials.json'));
        $this->client->addScope(Sheets::SPREADSHEETS);
        $this->spreadsheetId = env('GOOGLE_SHEETS_SPREADSHEET_ID');
        $this->service = new Sheets($this->client);
    }

    /**
     * Sincronización bidireccional
     * 
     * @param string $direction 'db-to-sheet' o 'sheet-to-db'
     * @param string $sheetRange Rango de la hoja (ej: 'Factura!A2:Q100')
     * @return array Resultado de la operación
     */
    public function syncData(string $direction = 'db-to-sheet', string $sheetRange = 'Factura!A1:Q100')
    {
        try {
            if ($direction === 'db-to-sheet') {
                return $this->exportToSheets($sheetRange);
            } elseif ($direction === 'sheet-to-db') {
                return $this->importFromSheets($sheetRange);
            } else {
                throw new \Exception("Dirección de sincronización no válida");
            }
        } catch (\Exception $e) {
            Log::error('Error en sincronización: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'exception' => $e
            ];
        }
    }

    /**
     * Exporta datos desde la base de datos a Google Sheets
     */
    protected function exportToSheets(string $range)
    {
        Log::info('Iniciando exportación DB → Google Sheets');
        
        $ripsData = RipsData::orderBy('year')->orderBy('month')->get();
        
        if ($ripsData->isEmpty()) {
            throw new \Exception("No hay datos en la base de datos para exportar");
        }
        
        // Preparar datos
        $sheetData = [
            [
                'Año', 'Mes', 'Régimen', 'Facturado', 
                'Consultas Especializadas', 'Interconsultas Hospitalarias',
                'Urgencias General', 'Urgencias Especialista',
                'Egresos Hospitalarios', 'Imagenología', 'Laboratorio',
                'Partos', 'Cesáreas', 'Cirugías', 'Terapia Física',
                'Terapia Respiratoria', 'Observaciones'
            ]
        ];
        
        foreach ($ripsData as $item) {
            $sheetData[] = [
                $item->year,
                $item->month,
                $item->regimen,
                $item->facturado,
                $item->consultas_especializada,
                $item->interconsultas_hospitalaria,
                $item->urgencias_general,
                $item->urgencias_especialista,
                $item->egresos_hospitalarios,
                $item->imagenologia,
                $item->laboratorio,
                $item->partos,
                $item->cesareas,
                $item->cirugias,
                $item->terapia_fisica,
                $item->terapia_respiratoria,
                $item->observaciones
            ];
        }
        
        // Escribir en Sheets
        $body = new ValueRange(['values' => $sheetData]);
        $params = ['valueInputOption' => 'USER_ENTERED']; // Mejor manejo de formatos
        
        $this->service->spreadsheets_values->update(
            $this->spreadsheetId,
            $range,
            $body,
            $params
        );
        
        $count = count($sheetData) - 1; // Excluyendo encabezados
        
        Log::info("Exportación exitosa. $count registros transferidos");
        return [
            'success' => true,
            'message' => "Datos exportados correctamente ($count registros)",
            'rows_exported' => $count
        ];
    }

    /**
     * Importa datos desde Google Sheets a la base de datos
     */
    protected function importFromSheets(string $range)
    {
        Log::info('Iniciando importación Google Sheets → DB');
        
        $sheetData = $this->readSheetData($range);
        
        if (empty($sheetData)) {
            throw new \Exception("No se encontraron datos en el rango especificado");
        }
        
        $processed = 0;
        $errors = 0;
        
        foreach ($sheetData as $index => $row) {
            try {
                if (count($row) < 4) { // Campos mínimos requeridos
                    Log::warning("Fila incompleta en posición " . ($index + 1));
                    $errors++;
                    continue;
                }
                
                RipsData::updateOrCreate(
                    [
                        'year' => $this->parseNumber($row[0]),
                        'month' => $this->parseNumber($row[1]),
                        'regimen' => $row[2]
                    ],
                    [
                        'facturado' => $this->parseNumber($row[3]),
                        'consultas_especializada' => $this->parseNumber($row[4] ?? 0),
                        'interconsultas_hospitalaria' => $this->parseNumber($row[5] ?? 0),
                        'urgencias_general' => $this->parseNumber($row[6] ?? 0),
                        'urgencias_especialista' => $this->parseNumber($row[7] ?? 0),
                        'egresos_hospitalarios' => $this->parseNumber($row[8] ?? 0),
                        'imagenologia' => $this->parseNumber($row[9] ?? 0),
                        'laboratorio' => $this->parseNumber($row[10] ?? 0),
                        'partos' => $this->parseNumber($row[11] ?? 0),
                        'cesareas' => $this->parseNumber($row[12] ?? 0),
                        'cirugias' => $this->parseNumber($row[13] ?? 0),
                        'terapia_fisica' => $this->parseNumber($row[14] ?? 0),
                        'terapia_respiratoria' => $this->parseNumber($row[15] ?? 0),
                        'observaciones' => $row[16] ?? null
                    ]
                );
                
                $processed++;
            } catch (\Exception $e) {
                Log::error("Error procesando fila {$index}: " . $e->getMessage());
                $errors++;
            }
        }
        
        $message = "Importación completada. $processed registros actualizados, $errors errores";
        Log::info($message);
        
        return [
            'success' => true,
            'message' => $message,
            'processed' => $processed,
            'errors' => $errors
        ];
    }

    /**
     * Lee datos de Google Sheets
     */
    protected function readSheetData(string $range)
    {
        $response = $this->service->spreadsheets_values->get(
            $this->spreadsheetId, 
            $range,
            ['majorDimension' => 'ROWS']
        );
        
        return $response->getValues() ?? [];
    }

    /**
     * Limpia y parsea valores numéricos
     */
    private function parseNumber($value)
    {
        if (is_null($value)) return 0;
        if (is_numeric($value)) return $value;
        
        $cleaned = preg_replace('/[^0-9.-]/', '', (string)$value);
        return (float)$cleaned ?: 0;
    }
}