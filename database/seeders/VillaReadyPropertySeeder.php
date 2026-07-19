<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VillaReadyProperty;
use App\Models\VillaReadyPropertyUnit;

class VillaReadyPropertySeeder extends Seeder
{
    public function run(): void
    {
        $property = VillaReadyProperty::create([
            'property_id' => 'VRC-MILNA-001',
            'title' => 'Premium EU Sea-View Property — Apartments and Villas in Milna, Brač',
            'short_title' => 'Milna Sea-View Development',
            'slug' => 'premium-sea-view-milna-brac',
            'location' => 'Milna, Island of Brač, Croatia',
            'intro' => 'Villa Ready Croatia presents a new premium residential development in Milna, on the Island of Brač, Croatia. The project is located above Marina Vlaška, close to the sea, beaches, restaurants, shops, the ferry port, marina, and the centre of Milna.',
            'description' => 'Villa Ready Croatia presents a new premium residential development in Milna, on the Island of Brač, Croatia. The project is located above Marina Vlaška, close to the sea, beaches, restaurants, shops, the ferry port, marina, and the centre of Milna.

The elevated position offers sea views, privacy, easy road access, and proximity to everything needed for holidays, private living, or rental use. The development includes four residential buildings with modern apartments, swimming pools, private outdoor areas, garages and storage.',
            'location_description' => 'Milna, Island of Brač, Croatia, near Marina Vlaška, beaches, marina, restaurants, shops, ferry connections and Milna centre.',
            'property_type' => 'New development',
            'buildings_count' => 4,
            'structure' => 'New four-building residential development with modern apartments, swimming pools, private outdoor areas, garages and storage.',
            'ground_floor_range' => '85–100 m²',
            'first_floor_range' => '110 m²',
            'attic_range' => '122 m²',
            'price_per_m2' => 5900.00,
            'price_display' => '€5,900 / m² net',
            'payment_structure' => '30% at the start and 70% when construction starts, subject to the final agreement.',
            'vat_info' => 'As a new development, the purchase is subject to Croatian VAT. Eligible Croatian VAT-registered companies using the property for taxable business activity may be able to deduct input VAT, subject to applicable rules and professional advice.',
            'use_options' => 'Private living, holidays, tourist rental income, long-term ownership or future resale.',
            'management_service' => 'Booking management, guest communication, cleaning, maintenance, rental pricing and local property supervision.',
            'disclaimer' => 'All information, prices, and availability are subject to change. Confirm all details with professional advisers before purchase.',
            'meta_title' => 'Premium Sea-View Property in Milna, Brač | Villa Ready Croatia',
            'meta_description' => 'New premium residential development in Milna, Island of Brač. Sea-view apartments from €5,900/m². 4 buildings, pools, garages. Buy through our agency at developer prices.',
            'commission_percent' => 6.00,
            'cookie_duration_days' => 180,
            'status' => 'published',
        ]);

        // Add units
        $units = [
            ['building_number' => 1, 'floor' => 'Ground-floor', 'unit_code' => 'A1', 'size_m2' => 85, 'net_price' => 501500, 'status' => 'available'],
            ['building_number' => 1, 'floor' => 'Ground-floor', 'unit_code' => 'A2', 'size_m2' => 100, 'net_price' => 590000, 'status' => 'available'],
            ['building_number' => 1, 'floor' => 'First-floor', 'unit_code' => 'B1', 'size_m2' => 110, 'net_price' => 649000, 'status' => 'available'],
            ['building_number' => 1, 'floor' => 'Attic', 'unit_code' => 'C1', 'size_m2' => 122, 'net_price' => 719800, 'status' => 'reserved'],
        ];

        foreach ($units as $unit) {
            VillaReadyPropertyUnit::create([
                'villa_ready_property_id' => $property->id,
                'building_number' => $unit['building_number'],
                'floor' => $unit['floor'],
                'unit_code' => $unit['unit_code'],
                'size_m2' => $unit['size_m2'],
                'net_price' => $unit['net_price'],
                'status' => $unit['status'],
            ]);
        }

        $this->command->info('Villa Ready Milna property created with ' . count($units) . ' units.');
    }
}
