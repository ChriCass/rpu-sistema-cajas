<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AsignarRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurarse de que los roles existan
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $tesoreroRole = Role::firstOrCreate(['name' => 'tesorero']);
        $supervisorMaquinariaRole = Role::firstOrCreate(['name' => 'supervisor maquinaria']);

        // Lista de correos o IDs de usuarios que serán admins
        $admins = [
            'abelardovs1998@hotmail.com',
            'chris_ccc68@yahoo.com.pe',
            'ricardorpu4@gmail.com',
        ];

        // Lista de correos o IDs de usuarios que serán tesoreros
        $tesoreros = [
            'dsr_61@hotmail.com',
            'dala.conta12@gmail.com',
        ];

        // Lista de correos o IDs de usuarios que serán supervisores de maquinaria
        $supervisoresMaquinaria = [
            // Añade aquí los correos de los usuarios que serán supervisores de maquinaria
        ];

        // Asignar roles a los admins
        foreach ($admins as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->assignRole($adminRole);
            }
        }

        // Asignar roles a los tesoreros
        foreach ($tesoreros as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->assignRole($tesoreroRole);
            }
        }

        // Asignar roles a los supervisores de maquinaria
        foreach ($supervisoresMaquinaria as $email) {
            $user = User::where('email', $email)->first();
            if ($user) {
                $user->assignRole($supervisorMaquinariaRole);
            }
        }
    }
}
