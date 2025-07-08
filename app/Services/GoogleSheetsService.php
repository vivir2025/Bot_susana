<?php

namespace App\Services;
use App\Models\RipsData; 
use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class GoogleSheetsService
{
    protected $client;
    protected $service;
    protected $spreadsheetId;

    public function __construct()
{
    $this->client = new Client();
    // Añade esto para debug
    if (!file_exists(storage_path('app/google-sheets-credentials.json'))) {
        throw new \Exception("Archivo de credenciales no encontrado");
    }
    $this->client->setAuthConfig(storage_path('app/google-sheets-credentials.json'));
    $this->client->addScope(Sheets::SPREADSHEETS);
    
    // Verifica el spreadsheetId
    $this->spreadsheetId = env('GOOGLE_SHEETS_SPREADSHEET_ID');
    if (empty($this->spreadsheetId)) {
        throw new \Exception("Spreadsheet ID no configurado en .env");
    }
    
    $this->service = new Sheets($this->client);
}

   public function readData($range)
{
    try {
        $response = $this->service->spreadsheets_values->get(
            $this->spreadsheetId, 
            $range,
            ['majorDimension' => 'ROWS']
        );
        
        \Log::info("Datos leídos de Sheets:", ['data' => $response->getValues()]);
        
        return $response->getValues() ?? [];
    } catch (\Exception $e) {
        \Log::error("Error al leer de Google Sheets: " . $e->getMessage());
        return [];
    }
}

    public function writeData($range, $data)
    {
        $valueRange = new ValueRange();
        $valueRange->setValues($data);
        
        $this->service->spreadsheets_values->update(
            $this->spreadsheetId,
            $range,
            $valueRange,
            ['valueInputOption' => 'RAW']
        );
    }

   public function syncRipsData()
{
    \Log::info('Iniciando sincronización de datos RIPS');
    
    try {
        // 1. Leer datos de Google Sheets
        $sheetData = $this->readData('Factura!A2:Q100'); // Ajustado a 17 columnas (A-Q)
        
        if (empty($sheetData)) {
            \Log::error('No se encontraron datos en Google Sheets');
            return ['success' => false, 'message' => 'No hay datos en la hoja'];
        }
        
        \Log::info('Datos leídos de Google Sheets', ['count' => count($sheetData)]);
        
        // 2. Procesar cada fila
        $processed = 0;
        $errors = 0;
        
        foreach ($sheetData as $index => $row) {
            try {
                // Verificar que la fila tenga al menos los campos obligatorios
                if (count($row) < 4) { // year, month, regimen y facturado son requeridos
                    \Log::warning('Fila incompleta', ['fila' => $index + 2, 'data' => $row]);
                    $errors++;
                    continue;
                }
                
                // 3. Insertar o actualizar en la base de datos
                $result = RipsData::updateOrCreate(
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
                \Log::error('Error procesando fila', [
                    'fila' => $index + 2,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $errors++;
            }
        }
        
        // 4. Verificar resultados
        $message = "Procesados: $processed registros. Errores: $errors";
        \Log::info($message);
        
        return [
            'success' => true,
            'message' => $message,
            'processed' => $processed,
            'errors' => $errors
        ];
        
    } catch (\Exception $e) {
        \Log::error('Error general en syncRipsData: ' . $e->getMessage());
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

private function parseNumber($value)
{
    if (is_null($value)) return 0;
    if (is_numeric($value)) return $value;
    
    // Limpiar formatos de moneda y otros caracteres
    $cleaned = preg_replace('/[^0-9.-]/', '', $value);
    return (float) $cleaned ?: 0;
 }
 };

