<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PDVAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Usamos updateOrCreate para evitar duplicatas caso o seeder rode mais de uma vez
        Employee::updateOrCreate(
            ['email' => 'admin.pdv@jumbonline.com.br'], // Condição de busca
            [
                'name' => 'Administrador do PDV',
                'password' => Hash::make('15D4aeb4'), // Lembre-se de trocar por uma senha forte em produção
                'is_admin' => true, // Flag crucial que define as permissões máximas no AuthController
                'email_verified_at' => now(),
                'bio' => 'Conta criada automaticamente para administração do PDV.',
            ]
        );

        $this->command->info('✅ Usuário Administrador do PDV criado/atualizado com sucesso!');
    }
}