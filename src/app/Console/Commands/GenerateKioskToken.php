<?php
namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class GenerateKioskToken extends Command
{
    protected $signature = 'kiosco:token {user? : ID del usuario al que asignar el token}';
    protected $description = 'Genera un token de Sanctum para el kiosco de la Raspberry Pi';

    public function handle(): int
    {
        $userId = $this->argument('user');

        if (!$userId) {
            $user = User::first();
            if (!$user) {
                $this->error('No hay usuarios en el sistema. Crea uno primero o proporciona un ID.');
                return Command::FAILURE;
            }
        } else {
            $user = User::find($userId);
            if (!$user) {
                $this->error("Usuario con ID {$userId} no encontrado.");
                return Command::FAILURE;
            }
        }

        $token = $user->createToken('kiosco-raspberry-pi', ['kiosk'])->plainTextToken;

        $this->info('Token generado exitosamente para: ' . $user->name . ' (' . $user->email . ')');
        $this->warn('GUARDA ESTE TOKEN, no se mostrará nuevamente:');
        $this->line('');
        $this->line($token);
        $this->line('');
        $this->info('Usa este token en el header: Authorization: Bearer ' . $token);

        return Command::SUCCESS;
    }
}
