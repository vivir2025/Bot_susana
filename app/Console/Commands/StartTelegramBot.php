<?php

namespace App\Console\Commands;

use App\Services\TelegramBotService;
use Illuminate\Console\Command;


class StartTelegramBot extends Command
{
    protected $signature = 'telegram:start';
    protected $description = 'Inicia el bot de Telegram para el sistema RIPS';

    public function handle()
    {
        $this->info('🚀 Iniciando bot de Telegram...');
        $this->info('Bot Token: ' . substr(env('TELEGRAM_BOT_TOKEN'), 0, 10) . '...');
        $this->info('Presiona Ctrl+C para detener el bot');
        
        try {
            $bot = new TelegramBotService();
            $bot->run();
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
        }
    }
}