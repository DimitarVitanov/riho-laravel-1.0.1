<?php

namespace Database\Seeders;

use App\Models\VillaReadyProperty;
use App\Models\VillaReadyPropertyImage;
use Illuminate\Database\Seeder;

class VillaReadyPropertyImagesSeeder extends Seeder
{
    public function run(): void
    {
        $property = VillaReadyProperty::first();
        if (!$property) {
            return;
        }

        // Images from the template - these are the hardcoded ones
        $images = [
            // Hero / 360 drone view
            ['path' => '/villa-ready-assets/1.webp', 'type' => '360', 'caption' => '360 Drone View', 'order' => 1],
            
            // Gallery images
            ['path' => '/villa-ready-assets/villareadycroatia1.jpg', 'type' => 'gallery', 'caption' => 'Villa Ready Croatia - Aerial View', 'order' => 2],
            ['path' => '/villa-ready-assets/villareadycroatia2.webp', 'type' => 'gallery', 'caption' => 'Villa Ready Croatia - Location', 'order' => 3],
            ['path' => '/villa-ready-assets/villareadycroatia3.webp', 'type' => 'gallery', 'caption' => 'Villa Ready Croatia - Chain Location', 'order' => 4],
            ['path' => '/villa-ready-assets/villareadycroatia4.webp', 'type' => 'sea_view', 'caption' => 'Sea View from Location', 'order' => 5],
            ['path' => '/villa-ready-assets/villareadycroatia5.jpg', 'type' => 'map', 'caption' => 'Map View of Location', 'order' => 6],
            ['path' => '/villa-ready-assets/villareadycroatia6.webp', 'type' => 'concept', 'caption' => 'Conceptual Development', 'order' => 7],
            ['path' => '/villa-ready-assets/villareadycroatia7.jpg', 'type' => 'aerial', 'caption' => 'Aerial Site Perspective', 'order' => 8],
            
            // Main villa visualization
            ['path' => '/villa-ready-assets/mainVilla.png', 'type' => 'concept', 'caption' => 'Main Villa Visualization', 'order' => 9],
            
            // Details image
            ['path' => '/villa-ready-assets/details.webp', 'type' => 'floor_plan', 'caption' => 'Building Details', 'order' => 10],
        ];

        foreach ($images as $img) {
            VillaReadyPropertyImage::updateOrCreate(
                [
                    'villa_ready_property_id' => $property->id,
                    'image_path' => $img['path'],
                ],
                [
                    'image_type' => $img['type'],
                    'caption' => $img['caption'],
                    'sort_order' => $img['order'],
                ]
            );
        }

        $this->command->info('Added ' . count($images) . ' images to property.');
    }
}
