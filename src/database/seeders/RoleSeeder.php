<?php
namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'name' => 'Administrador',
            'slug' => 'administrador',
            'description' => 'Acceso completo a todas las funcionalidades del sistema',
            'editable' => false,
            'permissions' => [
                'dashboard.view',
                'habitaciones.view', 'habitaciones.create', 'habitaciones.edit', 'habitaciones.delete',
                'reservas.view', 'reservas.create', 'reservas.edit', 'reservas.delete',
                'tarifas.view', 'tarifas.edit',
                'promociones.view', 'promociones.create', 'promociones.edit', 'promociones.delete',
                'productos.view', 'productos.create', 'productos.edit', 'productos.delete',
                'paquetes.view', 'paquetes.create', 'paquetes.edit', 'paquetes.delete',
                'ocupaciones.view', 'ocupaciones.delete',
                'feriados.view', 'feriados.create', 'feriados.delete',
                'usuarios.view', 'usuarios.create', 'usuarios.edit', 'usuarios.delete',
                'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
            ],
        ]);

        Role::create([
            'name' => 'Recepcionista',
            'slug' => 'recepcionista',
            'description' => 'Gestión de reservas, ocupaciones y consulta de datos',
            'editable' => true,
            'permissions' => [
                'dashboard.view',
                'habitaciones.view',
                'reservas.view', 'reservas.create', 'reservas.edit',
                'tarifas.view',
                'promociones.view',
                'productos.view',
                'paquetes.view',
                'ocupaciones.view',
                'feriados.view',
                'usuarios.view',
            ],
        ]);
    }
}
