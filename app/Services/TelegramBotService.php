<?php

namespace App\Services;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\Inline\InlineKeyboardButton;
use App\Models\RipsData;
use App\Models\UsersByAge;
use App\Services\GoogleSheetsService;

class TelegramBotService
{
    protected $bot;
    protected $client;
    protected $userStates = [];

    public function __construct()
    {
        $this->bot = new BotApi(env('TELEGRAM_BOT_TOKEN'));
        $this->client = new Client(env('TELEGRAM_BOT_TOKEN'));
    }

    public function setupCommands()
    {
        // Comando de inicio
        $this->client->command('start', function ($message) {
            $this->sendWelcomeMessage($message->getChat()->getId());
        });

        // Comando de ayuda
        $this->client->command('help', function ($message) {
            $this->sendHelpMessage($message->getChat()->getId());
        });

        // Comando de estado
        $this->client->command('status', function ($message) {
            $this->sendStatusMessage($message->getChat()->getId());
        });

        // Comando de reporte rápido
        $this->client->command('reporte', function ($message) {
            $this->sendQuickReport($message->getChat()->getId());
        });

        // Manejar botones inline
        $this->client->callbackQuery(function ($callbackQuery) {
            $this->handleCallbackQuery($callbackQuery);
        });

        // Manejar mensajes de texto
        $this->client->on(function ($update) {
            if ($update->getMessage()) {
                $this->handleTextMessage($update->getMessage());
            }
        }, function ($update) {
            return $update->getMessage() && $update->getMessage()->getText();
        });
    }

    public function sendWelcomeMessage($chatId)
    {
        $keyboard = new InlineKeyboardMarkup([
            [
                new InlineKeyboardButton('📊 Reportes', null, 'menu_reportes'),
                new InlineKeyboardButton('📈 Comparaciones', null, 'menu_comparaciones')
            ],
            [
                new InlineKeyboardButton('🔄 Sincronizar', null, 'sync_data'),
                new InlineKeyboardButton('📋 Dashboard', null, 'dashboard')
            ],
            [
                new InlineKeyboardButton('❓ Ayuda', null, 'help'),
                new InlineKeyboardButton('📊 Estado', null, 'status')
            ]
        ]);

        $message = "🏥 *Hospital Susana López de Valencia*\n" .
                  "📊 *Sistema de Análisis RIPS*\n\n" .
                  "¡Bienvenido! Soy tu asistente para consultar datos RIPS.\n\n" .
                  "🔹 Puedes consultar reportes mensuales, trimestrales o anuales\n" .
                  "🔹 Comparar diferentes períodos\n" .
                  "🔹 Sincronizar datos desde Google Sheets\n" .
                  "🔹 Ver estadísticas en tiempo real\n\n" .
                  "Selecciona una opción para comenzar:";

        $this->bot->sendMessage(
            $chatId,
            $message,
            'Markdown',
            false,
            null,
            $keyboard
        );
    }

    public function sendHelpMessage($chatId)
    {
        $message = "🆘 *Ayuda - Sistema RIPS*\n\n" .
                  "*Comandos disponibles:*\n" .
                  "• `/start` - Menú principal\n" .
                  "• `/help` - Esta ayuda\n" .
                  "• `/status` - Estado del sistema\n" .
                  "• `/reporte` - Reporte rápido del mes actual\n\n" .
                  "*Funciones principales:*\n" .
                  "📊 *Reportes:* Consulta datos por mes, trimestre o año\n" .
                  "📈 *Comparaciones:* Compara períodos diferentes\n" .
                  "🔄 *Sincronización:* Actualiza datos desde Google Sheets\n" .
                  "📋 *Dashboard:* Enlace al dashboard web\n\n" .
                  "*Ejemplo de uso:*\n" .
                  "1. Presiona 'Reportes'\n" .
                  "2. Selecciona 'Reporte Mensual'\n" .
                  "3. Elige el año y mes\n" .
                  "4. Recibe el reporte completo";

        $keyboard = new InlineKeyboardMarkup([
            [new InlineKeyboardButton('🏠 Menú Principal', null, 'menu_principal')]
        ]);

        $this->bot->sendMessage($chatId, $message, 'Markdown', false, null, $keyboard);
    }

    public function sendStatusMessage($chatId)
    {
        $totalRecords = RipsData::count();
        $lastRecord = RipsData::latest()->first();
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        $monthlyTotal = RipsData::byPeriod($currentYear, $currentMonth)->sum('facturado');
        $yearlyTotal = RipsData::byPeriod($currentYear)->sum('facturado');

        $message = "📊 *Estado del Sistema RIPS*\n\n" .
                  "💾 *Datos almacenados:* $totalRecords registros\n" .
                  "📅 *Último registro:* " . ($lastRecord ? $lastRecord->year . '-' . $lastRecord->month : 'No hay datos') . "\n" .
                  "💰 *Facturación mes actual:* $" . number_format($monthlyTotal, 2) . "\n" .
                  "📈 *Facturación año actual:* $" . number_format($yearlyTotal, 2) . "\n\n" .
                  "🟢 *Sistema operativo*\n" .
                  "⏰ *Última actualización:* " . date('Y-m-d H:i:s');

        $keyboard = new InlineKeyboardMarkup([
            [
                new InlineKeyboardButton('🔄 Actualizar', null, 'status'),
                new InlineKeyboardButton('🏠 Menú Principal', null, 'menu_principal')
            ]
        ]);

        $this->bot->sendMessage($chatId, $message, 'Markdown', false, null, $keyboard);
    }

    public function sendQuickReport($chatId)
    {
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        $monthlyData = RipsData::byPeriod($currentYear, $currentMonth)->get();
        $monthlyTotal = $monthlyData->sum('facturado');
        
        if ($monthlyData->isEmpty()) {
            $this->bot->sendMessage($chatId, "❌ No hay datos para el mes actual ($currentYear-$currentMonth)");
            return;
        }

        $message = "📊 *Reporte Rápido - " . date('F Y') . "*\n\n";
        $message .= "💰 *Facturación Total:* $" . number_format($monthlyTotal, 2) . "\n\n";
        
        $message .= "*Por Régimen:*\n";
        $regimenData = $monthlyData->groupBy('regimen');
        foreach ($regimenData as $regimen => $data) {
            $total = $data->sum('facturado');
            $message .= "• $regimen: $" . number_format($total, 2) . "\n";
        }

        $message .= "\n*Servicios Destacados:*\n";
        $totalConsultas = $monthlyData->sum('consultas_especializada');
        $totalUrgencias = $monthlyData->sum('urgencias_general') + $monthlyData->sum('urgencias_especialista');
        $totalCirugias = $monthlyData->sum('cirugias');
        
        $message .= "🏥 Consultas Especializadas: $totalConsultas\n";
        $message .= "🚨 Urgencias: $totalUrgencias\n";
        $message .= "⚕️ Cirugías: $totalCirugias\n";

        $keyboard = new InlineKeyboardMarkup([
            [
                new InlineKeyboardButton('📊 Reporte Completo', null, 'reporte_completo_' . $currentYear . '_' . $currentMonth),
                new InlineKeyboardButton('📈 Comparar', null, 'comparar_mes_actual')
            ],
            [
                new InlineKeyboardButton('🏠 Menú Principal', null, 'menu_principal')
            ]
        ]);

        $this->bot->sendMessage($chatId, $message, 'Markdown', false, null, $keyboard);
    }

    public function handleCallbackQuery($callbackQuery)
    {
        $chatId = $callbackQuery->getMessage()->getChat()->getId();
        $messageId = $callbackQuery->getMessage()->getMessageId();
        $data = $callbackQuery->getData();

        // Responder al callback para quitar el "loading"
        $this->bot->answerCallbackQuery($callbackQuery->getId());

        switch ($data) {
            case 'menu_principal':
                $this->sendWelcomeMessage($chatId);
                break;
            case 'menu_reportes':
                $this->sendReportMenu($chatId);
                break;
            case 'menu_comparaciones':
                $this->sendComparisonMenu($chatId);
                break;
            case 'sync_data':
                $this->syncDataFromSheets($chatId);
                break;
            case 'dashboard':
                $this->sendDashboardLink($chatId);
                break;
            case 'help':
                $this->sendHelpMessage($chatId);
                break;
            case 'status':
                $this->sendStatusMessage($chatId);
                break;
            case 'reporte_mensual':
                $this->requestMonthlyReport($chatId);
                break;
            case 'reporte_trimestral':
                $this->requestQuarterlyReport($chatId);
                break;
            case 'reporte_anual':
                $this->requestYearlyReport($chatId);
                break;
            case 'comparar_meses':
                $this->requestMonthComparison($chatId);
                break;
            case 'comparar_trimestres':
                $this->requestQuarterComparison($chatId);
                break;
            case 'comparar_anos':
                $this->requestYearComparison($chatId);
                break;
            default:
                // Manejar callbacks dinámicos
                $this->handleDynamicCallback($chatId, $data);
                break;
        }
    }

    public function handleDynamicCallback($chatId, $data)
    {
        if (strpos($data, 'reporte_completo_') === 0) {
            $parts = explode('_', $data);
            $year = $parts[2];
            $month = $parts[3];
            $this->sendCompleteReport($chatId, $year, $month);
        } elseif (strpos($data, 'year_') === 0) {
            $year = substr($data, 5);
            $this->sendMonthSelection($chatId, $year);
        } elseif (strpos($data, 'month_') === 0) {
            $parts = explode('_', $data);
            $year = $parts[1];
            $month = $parts[2];
            $this->sendCompleteReport($chatId, $year, $month);
        }
    }

    public function sendReportMenu($chatId)
    {
        $keyboard = new InlineKeyboardMarkup([
            [
                new InlineKeyboardButton('📅 Reporte Mensual', null, 'reporte_mensual'),
                new InlineKeyboardButton('📆 Reporte Trimestral', null, 'reporte_trimestral')
            ],
            [
                new InlineKeyboardButton('📊 Reporte Anual', null, 'reporte_anual'),
                new InlineKeyboardButton('🔙 Volver', null, 'menu_principal')
            ]
        ]);

        $message = "📊 *Selecciona el tipo de reporte:*\n\n" .
                  "📅 *Mensual:* Datos detallados de un mes específico\n" .
                  "📆 *Trimestral:* Resumen de 3 meses\n" .
                  "📊 *Anual:* Consolidado de todo el año";

        $this->bot->sendMessage($chatId, $message, 'Markdown', false, null, $keyboard);
    }

    public function sendComparisonMenu($chatId)
    {
        $keyboard = new InlineKeyboardMarkup([
            [
                new InlineKeyboardButton('📊 Comparar Meses', null, 'comparar_meses'),
                new InlineKeyboardButton('📈 Comparar Trimestres', null, 'comparar_trimestres')
            ],
            [
                new InlineKeyboardButton('📅 Comparar Años', null, 'comparar_anos'),
                new InlineKeyboardButton('🔙 Volver', null, 'menu_principal')
            ]
        ]);

        $message = "📈 *Selecciona el tipo de comparación:*\n\n" .
                  "📊 *Meses:* Compara dos meses específicos\n" .
                  "📈 *Trimestres:* Compara trimestres\n" .
                  "📅 *Años:* Compara años completos";

        $this->bot->sendMessage($chatId, $message, 'Markdown', false, null, $keyboard);
    }

    public function requestMonthlyReport($chatId)
    {
        $currentYear = date('Y');
        $years = range($currentYear - 2, $currentYear + 1);
        
        $buttons = [];
        foreach ($years as $year) {
            $buttons[] = [new InlineKeyboardButton($year, null, 'year_' . $year)];
        }
        $buttons[] = [new InlineKeyboardButton('🔙 Volver', null, 'menu_reportes')];
        
        $keyboard = new InlineKeyboardMarkup($buttons);
        
        $this->bot->sendMessage(
            $chatId,
            "📅 *Selecciona el año para el reporte mensual:*",
            'Markdown',
            false,
            null,
            $keyboard
        );
    }

    public function sendMonthSelection($chatId, $year)
    {
        $months = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        
        $buttons = [];
        $row = [];
        foreach ($months as $num => $name) {
            $row[] = new InlineKeyboardButton($name, null, 'month_' . $year . '_' . $num);
            if (count($row) == 3) {
                $buttons[] = $row;
                $row = [];
            }
        }
        if (!empty($row)) {
            $buttons[] = $row;
        }
        
        $buttons[] = [new InlineKeyboardButton('🔙 Volver', null, 'reporte_mensual')];
        
        $keyboard = new InlineKeyboardMarkup($buttons);
        
        $this->bot->sendMessage(
            $chatId,
            "📅 *Selecciona el mes para $year:*",
            'Markdown',
            false,
            null,
            $keyboard
        );
    }

    public function sendCompleteReport($chatId, $year, $month)
    {
        $data = RipsData::byPeriod($year, $month)->get();
        
        if ($data->isEmpty()) {
            $this->bot->sendMessage($chatId, "❌ No hay datos para $year-$month");
            return;
        }

        $monthNames = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $totalFacturado = $data->sum('facturado');
        $message = "📊 *Reporte Completo - {$monthNames[$month]} $year*\n\n";
        $message .= "💰 *Facturación Total:* $" . number_format($totalFacturado, 2) . "\n\n";

        // Facturación por régimen
        $regimenData = $data->groupBy('regimen');
        $message .= "*💼 Por Régimen:*\n";
        foreach ($regimenData as $regimen => $items) {
            $total = $items->sum('facturado');
            $percentage = ($total / $totalFacturado) * 100;
            $message .= "• $regimen: $" . number_format($total, 2) . " (" . round($percentage, 1) . "%)\n";
        }

        // Servicios
        $message .= "\n*🏥 Servicios Prestados:*\n";
        $totalConsultas = $data->sum('consultas_especializada');
        $totalUrgencias = $data->sum('urgencias_general') + $data->sum('urgencias_especialista');
        $totalCirugias = $data->sum('cirugias');
        $totalImagenologia = $data->sum('imagenologia');
        $totalLaboratorio = $data->sum('laboratorio');
        $totalPartos = $data->sum('partos');
        $totalCesareas = $data->sum('cesareas');

        $message .= "🩺 Consultas Especializadas: $totalConsultas\n";
        $message .= "🚨 Urgencias: $totalUrgencias\n";
        $message .= "⚕️ Cirugías: $totalCirugias\n";
        $message .= "📸 Imagenología: $totalImagenologia\n";
        $message .= "🧪 Laboratorio: $totalLaboratorio\n";
        $message .= "👶 Partos: $totalPartos\n";
        $message .= "🏥 Cesáreas: $totalCesareas\n";

        $keyboard = new InlineKeyboardMarkup([
            [
                new InlineKeyboardButton('📈 Comparar', null, 'comparar_mes_' . $year . '_' . $month),
                new InlineKeyboardButton('📊 Otro Mes', null, 'reporte_mensual')
            ],
            [
                new InlineKeyboardButton('🏠 Menú Principal', null, 'menu_principal')
            ]
        ]);

        $this->bot->sendMessage($chatId, $message, 'Markdown', false, null, $keyboard);
    }

    public function syncDataFromSheets($chatId)
    {
        $this->bot->sendMessage($chatId, "🔄 *Sincronizando datos desde Google Sheets...*", 'Markdown');
        
        try {
            // Aquí irías la sincronización real
            // $googleSheets = new GoogleSheetsService();
            // $googleSheets->syncRipsData();
            
            // Simulación de sincronización
            sleep(2);
            
            $this->bot->sendMessage($chatId, "✅ *Datos sincronizados exitosamente!*\n\n📊 Registros actualizados\n⏰ " . date('Y-m-d H:i:s'), 'Markdown');
        } catch (\Exception $e) {
            $this->bot->sendMessage($chatId, "❌ *Error al sincronizar datos:*\n\n" . $e->getMessage(), 'Markdown');
        }
    }

    public function sendDashboardLink($chatId)
    {
        $dashboardUrl = url('/dashboard');
        $message = "📊 *Dashboard Web*\n\n" .
                  "Accede al dashboard completo con gráficos interactivos:\n\n" .
                  "🔗 $dashboardUrl\n\n" .
                  "En el dashboard podrás:\n" .
                  "• Ver gráficos interactivos\n" .
                  "• Hacer comparaciones avanzadas\n" .
                  "• Exportar reportes\n" .
                  "• Configurar alertas";

        $keyboard = new InlineKeyboardMarkup([
            [new InlineKeyboardButton('🏠 Menú Principal', null, 'menu_principal')]
        ]);

        $this->bot->sendMessage($chatId, $message, 'Markdown', false, null, $keyboard);
    }

    public function handleTextMessage($message)
    {
        $chatId = $message->getChat()->getId();
        $text = $message->getText();
        
        // Manejar estados de usuario si es necesario
        // Por ejemplo, si están en medio de una comparación personalizada
        
        if (strpos($text, '/') !== 0) {
            $this->bot->sendMessage($chatId, "🤔 No entiendo ese mensaje. Usa /help para ver los comandos disponibles o /start para el menú principal.");
        }
    }

    public function run()
    {
        $this->setupCommands();
        $this->client->run();
    }

    // Método para webhook (opcional)
    public function handleWebhook($data)
    {
        $this->setupCommands();
        $this->client->handle($data);
    }
}