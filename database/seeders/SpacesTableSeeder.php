<?php

namespace Database\Seeders;

use App\Models\Space;
use Illuminate\Database\Seeder;

class SpacesTableSeeder extends Seeder
{
    public function run(): void
    {
        Space::factory()->count(10)->create();
    }
}
