<?php

namespace Database\Seeders;

use Database\Factories\AdministradorFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdministradoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        (new AdministradorFactory())->count(10)->create();
    }
}
