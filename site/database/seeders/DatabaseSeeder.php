<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ContentSeeder::class);

        // Fictional businesses, supporters and events for development only.
        if (! app()->isProduction()) {
            $this->call(DemoSeeder::class);
        }
    }
}
