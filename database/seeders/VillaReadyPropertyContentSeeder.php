<?php

namespace Database\Seeders;

use App\Models\VillaReadyProperty;
use App\Models\VillaReadyPropertyContent;
use Illuminate\Database\Seeder;

class VillaReadyPropertyContentSeeder extends Seeder
{
    public function run(): void
    {
        $property = VillaReadyProperty::first();
        if (!$property) {
            return;
        }

        VillaReadyPropertyContent::updateOrCreate(
            ['villa_ready_property_id' => $property->id],
            [
                'content_locked' => true,
                'content_lock_level' => 'locked',
                
                // Hero
                'hero_eyebrow' => '360° · Drone View · Milna · Island of Brač',
                'hero_chips' => ['WATCH A 360° DRONE VIEW FROM THE SKY', 'MILNA', 'ISLAND OF BRAČ'],
                'hero_media_label' => '360° Drone View',
                
                // Location Value
                'location_value_title' => 'UNDERSTAND LOCATION VALUE',
                'location_value_subtitle' => 'Why the supply is structurally limited on Brač and other Split-area islands.',
                'location_value_content' => "This is not a sales tactic. It is a structural reality of the Croatian islands — especially on islands where, in strategically located main places, new development is not allowed. On Brač, Milna and Supetar are the two main strategically located places.\n\nNew construction in coastal and natural zones is heavily restricted by law. Large parts of the island are protected, and available building-permitted land is both scarce and tightly regulated.\n\nAt the same time, demand continues to grow — driven by tourism, EU and worldwide buyers, and global interest in Croatian properties, backed by constantly increasing touristic demand.\n\nSo even if we ignore that building-permitted areas are rare, another angle is that these are small islands, literally with no space to grow anyway!\n\nYou understand why local people, when they see that someone on the Split-area islands like Brač and Hvar has property, consider you a rich man.\n\nThe result of any fact-based analysis is simple: Real limited supply + constant tourism demand = long-term value.\n\nThis is why opportunities like the one we offer here in Milna are not widely available — in fact, this is the ONLY EXISTING ONE for the new building of 25 modern apartments in a 4-villa chain area.",
                'location_value_highlight' => 'The result of any fact-based analysis is simple: Real limited supply + constant tourism demand = long-term value.',
                
                // Chain Location
                'chain_title' => 'THE 4-VILLA CHAIN LOCATION',
                'chain_subtitle' => 'A permitted development opportunity near the sea and Milna amenities.',
                'chain_content' => "Discover an exceptional opportunity to own land in one of Milna most attractive locations, with building permit availability near the sea and still within Milna amenities. Just above Marina Vlaška, this site offers both tranquillity and convenience.\n\nThis is an elite location that still exists in Milna and attracts all these values at the same time.\n\nYou can look around and see green zones that will stay green. You can see with your own eyes that there is no space for more building development, even if the government decides to give permits to anyone.",
                
                // Sea View
                'sea_title' => 'SEA VIEW FROM THE LOCATION',
                'sea_subtitle' => 'The view and access value of the site.',
                'sea_description' => 'Boasting a beautiful sea view from the villa location, with easy access from both the upper road and the charming Riva (promenade); the only available location ideal for a stylish, highest-value holiday home in Milna.',
                
                // Map
                'map_title' => 'MAP VIEW OF THE LOCATION',
                'map_subtitle' => 'Walking access to essential amenities and the sea.',
                'map_description' => 'Simplified map view shows easy access to all essential amenities — restaurants, shops, and the ferry port are all in close proximity, with a beautiful beach only 10 minutes away on foot. The first sea access is just 5 minutes walking away.',
                'map_coordinates' => '43.326, 16.450',
                
                // Stats
                'stat_total_area' => '4,283',
                'stat_plots' => '07',
                'stat_villas' => '04',
                'stat_apartments' => '24',
                'total_area_m2' => 4283,
                'plots_count' => 7,
                
                // Access
                'access_title' => 'LAND ACCESS & INFRASTRUCTURE OVERVIEW',
                'access_subtitle' => 'Connectivity, privacy and the access routes serving the project.',
                'access_intro' => 'The property benefits from a well-planned network of access roads, ensuring excellent connectivity while maintaining a sense of privacy and exclusivity. Below is a quick overview of the different access points that enhance the appeal and functionality of the site:',
                'access_cards' => [
                    ['title' => 'MAIN ACCESS ROAD', 'description' => 'While the main road circling Milna is not typically busy, the site is set just inside this route, offering both easy access and a sense of privacy from traffic.', 'order' => 1],
                    ['title' => 'SUPPORTING ACCESS ROAD', 'description' => 'A newly constructed road provides near-exclusive access to the plots, enhancing security and reducing traffic flow—ideal for a peaceful residential environment.', 'order' => 2],
                    ['title' => 'DIRECT ACCESS LANE', 'description' => 'A charming, narrow lane leads directly to the sea, offering the perfect route for walking or driving to the beach, ferry terminal, and nearby shops.', 'order' => 3],
                    ['title' => 'ADDITIONAL ACCESS ROAD', 'description' => 'According to the current Urban Plan, a roundabout is proposed in this area. While not yet constructed, it is likely to be replaced by a more modest, single-lane road.', 'order' => 4],
                ],
                
                // Plots
                'plots_title' => 'LAND PLOT SIZES (M²)',
                'plots_subtitle' => 'All seven original plot sizes.',
                'plot_sizes' => [
                    ['label' => 'SITE 1', 'size' => 724, 'order' => 1],
                    ['label' => 'SITE 2', 'size' => 614, 'order' => 2],
                    ['label' => 'SITE 3', 'size' => 494, 'order' => 3],
                    ['label' => 'SITE 4', 'size' => 568, 'order' => 4],
                    ['label' => 'SITE 5', 'size' => 568, 'order' => 5],
                    ['label' => 'SITE 6', 'size' => 568, 'order' => 6],
                    ['label' => 'SITE 7', 'size' => 747, 'order' => 7],
                ],
                
                // Concept
                'concept_title' => 'CONCEPTUAL SITE DEVELOPMENT',
                'concept_subtitle' => 'Original conceptual presentation supplied with the project.',
                'concept_disclaimer' => 'The images shown are conceptual visuals provided for illustrative purposes only. Final building designs and layouts will be developed in collaboration with an architect, tailored to your individual vision and preferences.',
                
                // Aerial
                'aerial_title' => 'AERIAL SITE PERSPECTIVE',
                'aerial_subtitle' => 'Original aerial project perspective.',
                
                // Pricing
                'pricing_title' => 'PRICING OPTIONS',
                'pricing_subtitle' => 'Exact payment, discount, building structure and price information from the source page.',
                'pricing_payment_text' => 'These are the actual prices, payable in a 2-step process: 30% at the start, and the remaining 70% when building starts.',
                'pricing_discount_text' => 'Villa Ready Croatia offers two major discount possibilities for a limited number of properties (maximum 50% of properties can have a discount, based on a first-come, first-served allocation):',
                'apartment_discount' => 10,
                'villa_discount' => 15,
                'custom_villa_option' => 'CUSTOM VILLA SETUP OPTION: People who want an ultra-custom villa that is not divided into apartments, but instead is a single standalone villa setup, can have it if the purchase is made early enough—before the final plan is fully executed.',
                'permitted_structure' => 'Basement + Ground Floor + 1st Floor + Attic',
                'basement_use' => 'Garage / storage',
                'pricing_currency' => 'EUR',
                'buildings_data' => [
                    [
                        'name' => 'BUILDING 1',
                        'gross' => 885,
                        'net' => 664,
                        'total' => 3917600,
                        'floors' => [
                            ['name' => 'GROUND FLOOR', 'unit_size' => 100, 'count' => 2, 'total_area' => 200, 'price' => 1180000],
                            ['name' => '1ST FLOOR', 'unit_size' => 110, 'count' => 2, 'total_area' => 220, 'price' => 1298000],
                            ['name' => 'ATTIC', 'unit_size' => 122, 'count' => 2, 'total_area' => 244, 'price' => 1439600],
                        ],
                    ],
                    [
                        'name' => 'BUILDING 2',
                        'gross' => 885,
                        'net' => 664,
                        'total' => 3917600,
                        'floors' => [
                            ['name' => 'GROUND FLOOR', 'unit_size' => 100, 'count' => 2, 'total_area' => 200, 'price' => 1180000],
                            ['name' => '1ST FLOOR', 'unit_size' => 110, 'count' => 2, 'total_area' => 220, 'price' => 1298000],
                            ['name' => 'ATTIC', 'unit_size' => 122, 'count' => 2, 'total_area' => 244, 'price' => 1439600],
                        ],
                    ],
                    [
                        'name' => 'BUILDING 3',
                        'gross' => 710,
                        'net' => 532.5,
                        'total' => 3141750,
                        'floors' => [
                            ['name' => 'GROUND FLOOR', 'unit_size' => 85, 'count' => 2, 'total_area' => 170, 'price' => 1003000],
                            ['name' => '1ST FLOOR', 'unit_size' => 90, 'count' => 2, 'total_area' => 180, 'price' => 1062000],
                            ['name' => 'ATTIC', 'unit_size' => 91.25, 'count' => 2, 'total_area' => 182.5, 'price' => 1076750],
                        ],
                    ],
                    [
                        'name' => 'BUILDING 4',
                        'gross' => 710,
                        'net' => 532.5,
                        'total' => 3141750,
                        'floors' => [
                            ['name' => 'GROUND FLOOR', 'unit_size' => 85, 'count' => 2, 'total_area' => 170, 'price' => 1003000],
                            ['name' => '1ST FLOOR', 'unit_size' => 90, 'count' => 2, 'total_area' => 180, 'price' => 1062000],
                            ['name' => 'ATTIC', 'unit_size' => 91.25, 'count' => 2, 'total_area' => 182.5, 'price' => 1076750],
                        ],
                    ],
                ],
                'total_project_price' => 14118700,
                
                // Tax
                'tax_title' => 'PRICING & TAX INFORMATION',
                'tax_subtitle' => 'Exact net price, VAT and ownership information from the source page.',
                'tax_intro' => "All prices listed for the properties are net prices, excluding applicable taxes.\n\nAs this is a newly developed real estate project sold by a Croatian company (d.o.o.), the transaction is subject to Value Added Tax (VAT) in accordance with Croatian law.",
                'non_eu_note' => "NOTE FOR NON-EU CITIZENS\n\nAny non-EU citizens need to register a Croatian local company in order to be owners, and that is the main option for them.\n\nOur All-Included Villa Management Service—a turnkey solution covering maintenance, rentals, and guest services—can be used, and within that service we can register and manage a local Croatian company for you.",
                'vat_rate' => 25,
                'tax_groups' => [
                    ['title' => 'VAT TREATMENT', 'order' => 1, 'items' => ['VAT (25%) is not included in the listed prices', 'VAT will be added to the purchase price at the time of sale', 'The sale is conducted under the VAT system for new developments']],
                    ['title' => 'FOR PRIVATE BUYERS', 'order' => 2, 'items' => ['Private individuals purchasing the property:', 'Pay the full purchase price + 25% VAT', 'VAT is a final cost and cannot be reclaimed']],
                    ['title' => 'FOR COMPANY BUYERS (EU / VAT REGISTERED)', 'order' => 3, 'items' => ['Companies registered within the VAT system:', 'Pay the purchase price + VAT', 'May be eligible to reclaim VAT, subject to their local tax regulations', 'This makes the acquisition significantly more efficient for investment purposes.']],
                    ['title' => 'EXAMPLE', 'order' => 4, 'items' => ['For a property priced at 500,000 EUR (net):', 'VAT (25%): 125,000 EUR', 'Total purchase price: 625,000 EUR']],
                    ['title' => 'SUMMARY', 'order' => 5, 'items' => ['All prices on this website are exclusive of VAT', 'VAT (25%) applies to all units', 'No additional real estate transfer tax applies to these properties', 'VAT may be recoverable for eligible company buyers']],
                ],
                
                // Investor
                'investor_title' => 'SCHEDULE A PRIVATE INVESTOR CALL',
                'investor_subtitle' => 'Connect directly for premium villa opportunities in Croatia.',
                'investor_content' => "Connect with our CEO directly to explore premium villa opportunities in Croatia. Villa Ready Croatia CEO personally handles all calls with potential investors.\n\nBook a personalized Zoom or WhatsApp video call at your convenience.",
                'investor_button' => 'BOOK A PRIVATE CALL',
                
                // Core Values
                'core_title' => 'CORE VALUES',
                'core_subtitle' => 'The complete project value proposition from the original page.',
                'core_values' => [
                    ['title' => 'Building in a Rare, Permitted Natural Area', 'description' => 'A unique opportunity to develop within a protected, low-density natural setting where new construction is highly limited—significantly increasing long-term value and exclusivity.', 'order' => 1],
                    ['title' => 'Privacy & Tranquility, Yet Close to Milna', 'description' => 'The property offers exceptional peace and seclusion while remaining just minutes from the charming coastal village of Milna, with its restaurants, marina, and daily amenities.', 'order' => 2],
                    ['title' => 'Sea View & Proximity to the Water', 'description' => 'Enjoy stunning sea views and direct access to the Adriatic, with the beach and marina within easy walking distance—ideal for a Mediterranean lifestyle.', 'order' => 3],
                    ['title' => 'Facing Split – The Heart of Dalmatia', 'description' => 'The location directly faces Split, Croatia second-largest city and a major international hub, offering convenient access to airports, ferries, and cultural attractions.', 'order' => 4],
                    ['title' => 'EU Legal Security & Ownership', 'description' => 'As part of the European Union, Croatia offers full legal protection for property buyers, transparent ownership processes, and access to EU-wide financial and legal frameworks.', 'order' => 5],
                    ['title' => 'Full-Service Villa Management Available', 'description' => 'Optional turnkey management services are available, covering everything from maintenance and rentals to guest services—ideal for owners seeking hassle-free investment returns.', 'order' => 6],
                    ['title' => 'Strong Rental Income Potential', 'description' => 'The Dalmatian coast is one of Europe top tourist destinations, ensuring high seasonal demand and excellent rental yields for well-located properties.', 'order' => 7],
                    ['title' => 'Long-Term Value Appreciation', 'description' => 'With limited supply, growing demand, and Croatia rising profile as a premium destination, properties in locations like Milna are positioned for sustained value growth.', 'order' => 8],
                ],
                
                // Summary
                'summary_title' => 'PROJECT SUMMARY',
                'summary_subtitle' => 'The source page concluding project assessment.',
                'summary_text' => 'This is a rare opportunity to develop in a naturally preserved yet fully permitted area, combining privacy, sea views, and immediate proximity to the vibrant marina town of Milna. With direct orientation toward Split—Dalmatia key international hub—the location offers both exclusivity and connectivity. Supported by EU legal security and optional full-service villa management, the project represents a compelling blend of lifestyle excellence and high-value investment potential.',
                
                // History
                'history_title' => 'HISTORY LESSON THAT CAN TELL YOU ALL',
                'history_subtitle' => 'The complete Diocletian and Milna story from the source page.',
                'history_content' => "Diocletian (ruled 284–305 AD) was born in nearby Salona, which today corresponds to the area of Solin, on the edge of Split. He built his famous palace in Split, just across the channel from Milna, Brač. After his abdication, he lived there until his death.\n\nWhen asked to return to power, he famously replied: If you could show the cabbage that I planted with my own hands to your emperor, he definitely would not dare suggest that I replace the peace and happiness of this place with the storms of a never-satisfied greed.\n\nDiocletian chose this coast for its unmatched beauty, mild climate, and peaceful seclusion. Nearly two millennia later, the same qualities make Milna and the island of Brač one of the most desirable locations in the Mediterranean.\n\nIf it was good enough for a Roman Emperor, it is good enough for us.",
                
                // Final Message
                'final_message' => "This property is marketed and sold through authorized real estate agencies. Contact the presenting agency for viewings, documentation, and purchase assistance.\n\nAll content on this page is provided by Villa Ready Croatia and presented by the agency on their behalf.",
                
                // Contact
                'contact_form_title' => 'CONTACT OUR AGENCY ABOUT THIS PROPERTY',
                'contact_form_subtitle' => 'The agency handles your questions, availability request, viewing and purchase communication.',
                'contact_button' => 'SEND ENQUIRY TO AGENCY',
                'contact_action' => '/property-enquiries',
                'agency_recipient_email' => 'agency@example.com',
                'agency_phone' => '+385 00 000 0000',
                'crm_pipeline' => 'Villa Ready Croatia Properties',
                'contact_interest_options' => "Request current availability\nRequest all plans and documents\nSchedule a private investor call\nAsk about company ownership and VAT",
                'require_name' => true,
                'require_email' => true,
                'require_phone' => false,
                
                // Sidebar
                'sidebar_price_label' => 'NET PRICE',
                'sidebar_price_value' => '5,900 EUR / m2 net',
                'sidebar_price_note' => 'Final unit price depends on floor and building selection. VAT (25%) applies.',
                'key_facts' => [
                    ['icon' => 'map-pin', 'text' => 'Milna, Island of Brač'],
                    ['icon' => 'euro', 'text' => 'Same approved developer price'],
                    ['icon' => 'home', 'text' => '4 villas, 24 apartments'],
                    ['icon' => 'ruler', 'text' => '4,283 m2 total area'],
                ],
                
                // SEO
                'schema_name' => 'Rare 4-Villa Development in Milna, Brač',
                'schema_address' => 'Milna, Island of Brač, Croatia',
                'schema_latitude' => '43.326',
                'schema_longitude' => '16.450',
                'date_published' => now(),
                'date_modified' => now(),
                
                // Media
                'logo_path' => '/villa-ready-assets/logo.webp',
                'decor_lines_path' => '/villa-ready-assets/lines.webp',
                'decor_dot_path' => '/villa-ready-assets/dot.webp',
            ]
        );
    }
}
