<?php

namespace Database\Seeders;

use App\Models\LandingPage;
use Illuminate\Database\Seeder;

class LandingPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $baseName = config('app.name');
        $current_year = date('Y');
        $app_url = env('APP_URL');
        $content = [
            "header" => [
                "logo" => "/frontend/images/landing/landing_logo.png",
                "menus" => [
                    "Home",
                    "Page",
                    "Feature",
                ],
                "status" => "1",
                "btn_url" => "https://themeforest.net/user/pixelstrap/portfolio",
                "btn_text" => "Buy Now",
                "links" => [
                    'link1' => [
                        'text' => 'Portfolio',
                        'url' => 'https://themeforest.net/user/pixelstrap/portfolio',
                    ],
                    'link2' => [
                        'text' => 'Hire Us',
                        'url' => 'https://docs.google.com/forms/d/e/1FAIpQLSe6hKUXw_By-pg7yabL0FxoTM02ZPTxoXy8PE3yboRuUCuyeA/viewform',
                    ],
                    'link3' => [
                        'text' => 'Documentation',
                        'url' => 'https://docs.pixelstrap.net/laravel/riho/',
                    ],
                ],
            ],
            "home" => [
                'title' => '<h1 class="text-center">The Premium Choice<span class="d-flex align-items-center justify-content-center pt-2 sub-content"><span>Admin</span>
                      <button class="animate-button-slide"><span class="notification-slider"><span class="d-flex h-100"><span class="mb-0 f-w-400"> <span class="font-primary">Ecommerce</span></span><i class="icon-arrow-top-right f-light"> </i></span><span class="d-flex h-100"><span class="mb-0 f-w-400"><span class="f-light">PROJECT</span></span></span><span class="d-flex h-100"> <span class="mb-0 f-w-400"><span class="f-light">Default</span></span></span></span></button><span>Laravel Template</span></span></h1>',
                'description' => 'The incredible and user-friendly Laravel was created using flexible, contemporary, and strong unique parts.',
                'main_img' => 'frontend/images/landing/demo/dashboard-1.png',
                'sidebar_cuts_image' => 'frontend/images/landing/sidebarcuts.png',
                'left_bottom_image' => 'frontend/images/landing/totalrevenue.png',
                'left_top_image' => 'frontend/images/landing/selling-product.png',
                'right_top_image' => 'frontend/images/landing/nft-marketplace.png',
                'right_bottom_image' => 'frontend/images/landing/newuser.png',
                'laravel_crud' => [
                    'sub_title' => 'Laravel CRUD Functionality',
                    'title' => 'Ready to use Laravel CRUDs',
                    'crud_banners' => [
                        [
                            'title' => 'Roles',
                            'description' => 'Manage user roles, permissions, and access control effectively.',
                            'image' => 'frontend/images/landing/layout-images/1.png',
                            'url' => "{$app_url}/admin/role",
                            'bg_color' => 'primary',
                        ],
                        [
                            'title' => 'Users',
                            'description' => 'Effortless Laravel user management with powerful theme integration.',
                            'image' => 'frontend/images/landing/layout-images/2.png',
                            'url' => "{$app_url}/admin/user",
                            'bg_color' => 'secondary',
                        ],
                        [
                            'title' => 'Blogs',
                            'description' => 'Efficient Laravel Blog Theme for Seamless Content Management.',
                            'image' => 'frontend/images/landing/layout-images/3.png',
                            'url' => "{$app_url}/admin/blog",
                            'bg_color' => 'warning',
                        ],
                        [
                            'title' => 'Categories',
                            'description' => 'Efficient Laravel Categories Management with CRUD, validation, filters.',
                            'image' => 'frontend/images/landing/layout-images/4.png',
                            'url' => "{$app_url}/admin/category",
                            'bg_color' => 'secondary',
                        ],
                        [
                            'title' => 'Tags',
                            'description' => 'Efficient Laravel theme tags management for organized content.',
                            'image' => 'frontend/images/landing/layout-images/5.png',
                            'url' => "{$app_url}/admin/tag",
                            'bg_color' => 'warning',
                        ],
                        [
                            'title' => 'Pages',
                            'description' => 'Efficiently manage Laravel theme pages with seamless content.',
                            'image' => 'frontend/images/landing/layout-images/6.png',
                            'url' => "{$app_url}/admin/page",
                            'bg_color' => 'primary',
                        ],
                    ],
                ],
            ],
            "page" => [
                "sub_title" => "Trending & Clean Design Collection",
                "title" => "Creative & Unique Admin Layout",
                "dashboard" => [
                    [
                        "title" => "Default Dashboard",
                        "url" => "{$app_url}/admin/default-dashboard",
                        "image" => "assets/images/landing/demo/3.jpg",
                    ],
                    [
                        "title" => "E-commerce Dashboard",
                        "url" => "{$app_url}/admin/ecommerce-dashboard",
                        "image" => "assets/images/landing/demo/1.jpg",
                    ],
                    [
                        "title" => "Project Dashboard",
                        "url" => "{$app_url}/admin/project-dashboard",
                        "image" => "assets/images/landing/demo/2.jpg",
                    ],
                ],
                "frameworks" => [
                    "sub_title" => "10+ frameworks available",
                    "title" => "Top Frameworks",
                    "poster" => [
                        [
                            'image' => 'frontend/images/landing/icon/laravel/laravel.png',
                            'title' => 'Laravel 12',
                        ],
                        [
                            'image' => 'frontend/images/landing/icon/laravel/bootstrap.png',
                            'title' => 'Bootstrap 5x',
                        ],
                        [
                            'image' => 'frontend/images/landing/icon/laravel/css.png',
                            'title' => 'CSS',
                        ],
                        [
                            'image' => 'frontend/images/landing/icon/laravel/sass.png',
                            'title' => 'Sass',
                        ],
                        [
                            'image' => 'frontend/images/landing/icon/laravel/blade.png',
                            'title' => 'Blade',
                        ],
                        [
                            'image' => 'frontend/images/landing/icon/laravel/nojquery.png',
                            'title' => 'Jquery',
                        ],
                        [
                            'image' => 'frontend/images/landing/icon/laravel/layout.png',
                            'title' => 'layouts',
                        ],
                        [
                            'image' => 'frontend/images/landing/icon/laravel/npm.png',
                            'title' => 'NPM',
                        ],
                        [
                            'image' => 'frontend/images/landing/icon/laravel/vite.png',
                            'title' => 'Vite',
                        ],
                        [
                            'image' => 'frontend/images/landing/icon/laravel/forms.png',
                            'title' => 'Forms',
                        ],
                        [
                            'image' => 'frontend/images/landing/icon/laravel/table.png',
                            'title' => 'Tables',
                        ],
                        [
                            'image' => 'frontend/images/landing/icon/laravel/uikits.png',
                            'title' => 'UI Kites',
                        ],
                    ],

                ],
                "applications" => [
                    "sub_title" => "Usefull Application",
                    "title" => "Fast & Powerful Applications",
                    'poster' => [
                        [
                            'image' => 'assets/images/landing/application/1.jpg',
                            'title' => 'Social App',
                            'url' => "{$app_url}/admin/social-app",
                        ],
                        [
                            'image' => 'assets/images/landing/application/2.jpg',
                            'title' => 'Knowledgebase',
                            'url' => "{$app_url}/admin/knowledgebase",
                        ],
                        [
                            'image' => 'assets/images/landing/application/3.jpg',
                            'title' => 'Support Ticket',
                            'url' => "{$app_url}/admin/support-ticket",
                        ],
                        [
                            'image' => 'assets/images/landing/application/4.jpg',
                            'title' => 'Letter box',
                            'url' => "{$app_url}/admin/letter-box",
                        ],
                        [
                            'image' => 'assets/images/landing/application/5.jpg',
                            'title' => 'To-Do',
                            'url' => "{$app_url}/admin/to-do",
                        ],
                        [
                            'image' => 'assets/images/landing/application/6.jpg',
                            'title' => 'Job Search',
                            'url' => "{$app_url}/admin/job-cards-view",
                        ],
                        [
                            'image' => 'assets/images/landing/application/7.jpg',
                            'title' => 'Ecommerce',
                            'url' => "{$app_url}/admin/products",
                        ],
                        [
                            'image' => 'assets/images/landing/application/8.jpg',
                            'title' => 'Faq',
                            'url' => "{$app_url}/admin/faq",
                        ],
                        [
                            'image' => 'assets/images/landing/application/9.jpg',
                            'title' => 'File Manager',
                            'url' => "{$app_url}/admin/file-manager",
                        ],
                        [
                            'image' => 'assets/images/landing/application/10.jpg',
                            'title' => 'Project',
                            'url' => "{$app_url}/admin/project-list",
                        ],
                        [
                            'image' => 'assets/images/landing/application/11.jpg',
                            'title' => 'Contacts',
                            'url' => "{$app_url}/admin/contacts",
                        ],
                        [
                            'image' => 'assets/images/landing/application/12.jpg',
                            'title' => 'Chat',
                            'url' => "{$app_url}/admin/chat-private",
                        ],
                    ],
                ],
            ],
            "feature" => [
                "laravel_feature" => [
                    "sub_title" => "Why you choose {$baseName}",
                    "title" => "Unique Laravel",
                    "poster" => [
                        [
                            "title" => "Quality & Clean Code",
                            "description" => "All you need to know of using clean code as a manager to make your team and your software awesome.",
                            "svg_icon" => "frontend/images/landing/feature-icon/1.svg",
                        ],
                        [
                            "title" => "Bootstrap v5.0",
                            "description" => "Powerful feature-packed frontend toolkit. Build and customize with Sass, utilize prebuilt grid system.",
                            "svg_icon" => "frontend/images/landing/feature-icon/2.svg",
                        ],
                        [
                            "title" => "Handmade Icons",
                            "description" => "let’s learn how to svg icons in {$baseName} admin template, handmade icons different materials.",
                            "svg_icon" => "frontend/images/landing/feature-icon/3.svg",
                        ],
                        [
                            "title" => "Limitless Components",
                            "description" => "The limitless layout collection and UI kit biggest collection of layouts for web design.",
                            "svg_icon" => "frontend/images/landing/feature-icon/4.svg",
                        ],
                        [
                            "title" => "Easy Customizable",
                            "description" => "Easy Step-By-Step Guide for Beginners.customize your layout, settings and content.",
                            "svg_icon" => "frontend/images/landing/feature-icon/5.svg",
                        ],
                        [
                            "title" => "Responsive",
                            "description" => "Use Responsive Design to Connect with all Device designing your website for mobile devices.",
                            "svg_icon" => "frontend/images/landing/feature-icon/6.svg",
                        ],
                        [
                            "title" => "Premium Support",
                            "description" => "We are always be their for your support and you are facing some issues you can create ticket.",
                            "svg_icon" => "frontend/images/landing/feature-icon/7.svg",
                        ],
                        [
                            "title" => "Colors Options",
                            "description" => "{$baseName} provide unlimited main color option.other colors you can change easily using scss variables.",
                            "svg_icon" => "frontend/images/landing/feature-icon/8.svg",
                        ],
                    ],
                ],
                "premium_support" => [
                    "title" => "we give it as we think that excellent support is needed",
                    "description" => "fast issue resolution, and dedicated experts for a seamless experience",
                    "button_text" => "Premium Support",
                    "button_url" => "https://support.pixelstrap.com/portal/en/signin",
                    "left_side_title" => "Our License",
                    "main_image" => "frontend/images/landing/unique.png",
                    "right_side_title" => "Premium Support",
                    "marquee_title" => "Premium Support. Our License . Premium Support .",
                ],
            ],
            'footer' => [
                'logo' => "frontend/images/logo/logo.png",
                'description' => "{$baseName} Globally Trusted Laravel Admin Theme",
                'copyright_text' => "Copyright {$current_year} © {$baseName} All rights reserved.",
                'left_button_text' => "Check Now",
                'left_button_url' => "{$app_url}/admin/default-dashboard",
                'right_button_text' => "Rate Us",
                'right_button_url' => "https://themeforest.net/user/pixelstrap/portfolio",
                'middle_button_text' => "Buy Now",
                'middle_button_url' => "https://themeforest.net/user/pixelstrap/portfolio",
            ],
        ];
        LandingPage::updateOrCreate(['content' => $content]);
    }
}
