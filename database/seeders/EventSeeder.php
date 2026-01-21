<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::create([
            'title' => 'Bersih Pantai',
            'description' => 'Kegiatan sosial membersihkan area pantai',
            'event_date' => now()->addDays(7),
            'user_id' => 1,
        ]);

        Event::create([
            'title' => 'Donor Darah Bersama',
            'description' => 'Aksi kemanusiaan donor darah',
            'event_date' => now()->addDays(14),
            'user_id' => 2,
        ]);

        Event::factory()->count(10)->create();
    }
}
