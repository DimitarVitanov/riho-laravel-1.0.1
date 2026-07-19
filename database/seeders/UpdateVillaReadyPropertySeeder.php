<?php

namespace Database\Seeders;

use App\Models\VillaReadyProperty;
use Illuminate\Database\Seeder;

class UpdateVillaReadyPropertySeeder extends Seeder
{
    public function run(): void
    {
        $property = VillaReadyProperty::first();
        if (!$property) {
            return;
        }

        $property->update([
            'hero_eyebrow' => '360 Drone View Milna Island of Brac',
            'hero_chips' => ['WATCH A 360 DRONE VIEW FROM THE SKY', 'MILNA', 'ISLAND OF BRAC'],
            'building_1_description' => 'Building 1 features 6 premium apartments across three floors with sea views and modern finishes. Ground floor units offer direct garden access, while upper floors provide panoramic sea views.',
            'building_2_description' => 'Building 2 mirrors Building 1 with 6 apartments, offering the same high-quality construction and layouts. Positioned for optimal morning sunlight and marina views.',
            'building_3_description' => 'Building 3 is a slightly smaller villa with 6 apartments, perfect for those seeking a more intimate setting with excellent privacy and sea views.',
            'building_4_description' => 'Building 4 completes the chain with 6 apartments, positioned at the highest point for maximum privacy and unobstructed views of the Adriatic.',
        ]);

        $this->command->info('Updated property ID: ' . $property->id);
    }
}
