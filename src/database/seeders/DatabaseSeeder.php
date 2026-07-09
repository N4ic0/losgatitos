<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            HabitacionSeeder::class,
            TarifaSeeder::class,
            ProductoSeeder::class,
            PromocionSeeder::class,
            ConfiguracionSeeder::class,
        ]);
    }
}
