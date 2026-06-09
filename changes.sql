UPDATE `users` SET `password` = '$2y$12$BtUbxrY0xfWiT6fWwT7s6eokEao3Y7fcaUyL413iOOzdFbkTCsAky', `email` = 'user@example.com', `deleted_at` = NULL WHERE `users`.`id` = 2;

-- modules table

UPDATE `modules` SET `actions` = '{\"index\":\"user.index\",\"create\":\"user.create\",\"edit\":\"user.edit\",\"destroy\":\"user.destroy\"}', `deleted_at` = NULL WHERE `modules`.`id` = 1;

UPDATE `modules` SET `actions` = '{\"index\":\"role.index\",\"create\":\"role.create\",\"edit\":\"role.edit\",\"destroy\":\"role.destroy\"}', `deleted_at` = NULL WHERE `modules`.`id` = 2;

UPDATE `modules` SET `actions` = '{\"index\":\"category.index\",\"create\":\"category.create\",\"edit\":\"category.edit\",\"destroy\":\"category.destroy\"}', `deleted_at` = NULL WHERE `modules`.`id` = 4;

UPDATE `modules` SET `actions` = '{\"index\":\"tag.index\",\"create\":\"tag.create\",\"edit\":\"tag.edit\",\"destroy\":\"tag.destroy\"}', `deleted_at` = NULL WHERE `modules`.`id` = 5;

UPDATE `modules` SET `actions` = '{\"index\":\"blog.index\",\"create\":\"blog.create\",\"edit\":\"blog.edit\",\"destroy\":\"blog.destroy\"}', `deleted_at` = NULL WHERE `modules`.`id` = 6;

UPDATE `modules` SET `actions` = '{\"index\":\"page.index\",\"create\":\"page.create\",\"edit\":\"page.edit\",\"destroy\":\"page.destroy\"}', `deleted_at` = NULL WHERE `modules`.`id` = 7;

DELETE FROM `modules` WHERE `modules`.`id` = 3;

-- permissions table 

DELETE FROM `permissions` WHERE `permissions`.`id` = 5;

DELETE FROM `permissions` WHERE `permissions`.`id` = 6;

DELETE FROM `permissions` WHERE `permissions`.`id` = 11;

DELETE FROM `permissions` WHERE `permissions`.`id` = 12;

DELETE FROM `permissions` WHERE `permissions`.`id` = 13;

DELETE FROM `permissions` WHERE `permissions`.`id` = 22;

DELETE FROM `permissions` WHERE `permissions`.`id` = 23;

DELETE FROM `permissions` WHERE `permissions`.`id` = 28;

DELETE FROM `permissions` WHERE `permissions`.`id` = 29;

DELETE FROM `permissions` WHERE `permissions`.`id` = 34;

DELETE FROM `permissions` WHERE `permissions`.`id` = 35;

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `values` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `values`, `created_at`, `updated_at`) VALUES
(1, '{\"general\":{\"light_logo\":\"\\/assets\\/images\\/logo\\/logo.png\",\"dark_logo\":\"\\/assets\\/images\\/logo\\/logo_dark.png\",\"favicon\":\"\\/assets\\/images\\/favicon.png\",\"site_name\":\"Riho\",\"footer\":\"Copyright 2025 \\u00a9 Riho theme by pixelstrap\"},\"google_reCaptcha\":{\"site_key\":null,\"secret\":null,\"status\":1},\"social_login\":{\"google\":{\"google_client_id\":null,\"google_client_secret\":null,\"google_redirect_url\":null,\"google_login_status\":1},\"facebook\":{\"facebook_client_id\":null,\"facebook_client_secret\":null,\"facebook_redirect_url\":null,\"facebook_login_status\":1}}}', '2025-06-09 00:44:05', '2025-06-09 00:44:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

--
-- Table structure for table `landing_pages`
--

CREATE TABLE `landing_pages` (
  `id` bigint UNSIGNED NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `landing_pages`
--

INSERT INTO `landing_pages` (`id`, `content`, `created_at`, `updated_at`) VALUES
(1, '{\"header\":{\"logo\":\"\\/frontend\\/images\\/landing\\/landing_logo.png\",\"menus\":[\"Home\",\"Page\",\"Feature\"],\"status\":\"1\",\"btn_url\":\"https:\\/\\/themeforest.net\\/user\\/pixelstrap\\/portfolio\",\"btn_text\":\"Buy Now\",\"links\":{\"link1\":{\"text\":\"Portfolio\",\"url\":\"https:\\/\\/themeforest.net\\/user\\/pixelstrap\\/portfolio\"},\"link2\":{\"text\":\"Hire Us\",\"url\":\"https:\\/\\/docs.google.com\\/forms\\/d\\/e\\/1FAIpQLSe6hKUXw_By-pg7yabL0FxoTM02ZPTxoXy8PE3yboRuUCuyeA\\/viewform\"},\"link3\":{\"text\":\"Documentation\",\"url\":\"https:\\/\\/docs.pixelstrap.net\\/laravel\\/riho\\/\"}}},\"home\":{\"title\":\"<h1 class=\\\"text-center\\\">The Premium Choice<span class=\\\"d-flex align-items-center justify-content-center pt-2 sub-content\\\"><span>Admin<\\/span>\\n                      <button class=\\\"animate-button-slide\\\"><span class=\\\"notification-slider\\\"><span class=\\\"d-flex h-100\\\"><span class=\\\"mb-0 f-w-400\\\"> <span class=\\\"font-primary\\\">Ecommerce<\\/span><\\/span><i class=\\\"icon-arrow-top-right f-light\\\"> <\\/i><\\/span><span class=\\\"d-flex h-100\\\"><span class=\\\"mb-0 f-w-400\\\"><span class=\\\"f-light\\\">PROJECT<\\/span><\\/span><\\/span><span class=\\\"d-flex h-100\\\"> <span class=\\\"mb-0 f-w-400\\\"><span class=\\\"f-light\\\">Default<\\/span><\\/span><\\/span><\\/span><\\/button><span>Laravel Template<\\/span><\\/span><\\/h1>\",\"description\":\"The incredible and user-friendly Laravel was created using flexible, contemporary, and strong unique parts.\",\"main_img\":\"frontend\\/images\\/landing\\/demo\\/dashboard-1.png\",\"sidebar_cuts_image\":\"frontend\\/images\\/landing\\/sidebarcuts.png\",\"left_bottom_image\":\"frontend\\/images\\/landing\\/totalrevenue.png\",\"left_top_image\":\"frontend\\/images\\/landing\\/selling-product.png\",\"right_top_image\":\"frontend\\/images\\/landing\\/nft-marketplace.png\",\"right_bottom_image\":\"frontend\\/images\\/landing\\/newuser.png\",\"laravel_crud\":{\"sub_title\":\"Laravel CRUD Functionality\",\"title\":\"Ready to use Laravel CRUDs\",\"crud_banners\":[{\"title\":\"Roles\",\"description\":\"Manage user roles, permissions, and access control effectively.\",\"image\":\"frontend\\/images\\/landing\\/layout-images\\/1.png\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/role\",\"bg_color\":\"primary\"},{\"title\":\"Users\",\"description\":\"Effortless Laravel user management with powerful theme integration.\",\"image\":\"frontend\\/images\\/landing\\/layout-images\\/2.png\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/user\",\"bg_color\":\"secondary\"},{\"title\":\"Blogs\",\"description\":\"Efficient Laravel Blog Theme for Seamless Content Management.\",\"image\":\"frontend\\/images\\/landing\\/layout-images\\/3.png\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/blog\",\"bg_color\":\"warning\"},{\"title\":\"Categories\",\"description\":\"Efficient Laravel Categories Management with CRUD, validation, filters.\",\"image\":\"frontend\\/images\\/landing\\/layout-images\\/4.png\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/category\",\"bg_color\":\"secondary\"},{\"title\":\"Tags\",\"description\":\"Efficient Laravel theme tags management for organized content.\",\"image\":\"frontend\\/images\\/landing\\/layout-images\\/5.png\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/tag\",\"bg_color\":\"warning\"},{\"title\":\"Pages\",\"description\":\"Efficiently manage Laravel theme pages with seamless content.\",\"image\":\"frontend\\/images\\/landing\\/layout-images\\/6.png\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/page\",\"bg_color\":\"primary\"}]}},\"page\":{\"sub_title\":\"Trending & Clean Design Collection\",\"title\":\"Creative & Unique Admin Layout\",\"dashboard\":[{\"title\":\"Default Dashboard\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/default-dashboard\",\"image\":\"assets\\/images\\/landing\\/demo\\/3.jpg\"},{\"title\":\"E-commerce Dashboard\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/ecommerce-dashboard\",\"image\":\"assets\\/images\\/landing\\/demo\\/1.jpg\"},{\"title\":\"Project Dashboard\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/project-dashboard\",\"image\":\"assets\\/images\\/landing\\/demo\\/2.jpg\"}],\"frameworks\":{\"sub_title\":\"10+ frameworks available\",\"title\":\"Top Frameworks\",\"poster\":[{\"image\":\"frontend\\/images\\/landing\\/icon\\/laravel\\/laravel.png\",\"title\":\"Laravel 12\"},{\"image\":\"frontend\\/images\\/landing\\/icon\\/laravel\\/bootstrap.png\",\"title\":\"Bootstrap 5x\"},{\"image\":\"frontend\\/images\\/landing\\/icon\\/laravel\\/css.png\",\"title\":\"CSS\"},{\"image\":\"frontend\\/images\\/landing\\/icon\\/laravel\\/sass.png\",\"title\":\"Sass\"},{\"image\":\"frontend\\/images\\/landing\\/icon\\/laravel\\/blade.png\",\"title\":\"Blade\"},{\"image\":\"frontend\\/images\\/landing\\/icon\\/laravel\\/nojquery.png\",\"title\":\"Jquery\"},{\"image\":\"frontend\\/images\\/landing\\/icon\\/laravel\\/layout.png\",\"title\":\"layouts\"},{\"image\":\"frontend\\/images\\/landing\\/icon\\/laravel\\/npm.png\",\"title\":\"NPM\"},{\"image\":\"frontend\\/images\\/landing\\/icon\\/laravel\\/vite.png\",\"title\":\"Vite\"},{\"image\":\"frontend\\/images\\/landing\\/icon\\/laravel\\/forms.png\",\"title\":\"Forms\"},{\"image\":\"frontend\\/images\\/landing\\/icon\\/laravel\\/table.png\",\"title\":\"Tables\"},{\"image\":\"frontend\\/images\\/landing\\/icon\\/laravel\\/uikits.png\",\"title\":\"UI Kites\"}]},\"applications\":{\"sub_title\":\"Usefull Application\",\"title\":\"Fast & Powerful Applications\",\"poster\":[{\"image\":\"assets\\/images\\/landing\\/application\\/1.jpg\",\"title\":\"Social App\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/social-app\"},{\"image\":\"assets\\/images\\/landing\\/application\\/2.jpg\",\"title\":\"Knowledgebase\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/knowledgebase\"},{\"image\":\"assets\\/images\\/landing\\/application\\/3.jpg\",\"title\":\"Support Ticket\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/support-ticket\"},{\"image\":\"assets\\/images\\/landing\\/application\\/4.jpg\",\"title\":\"Letter box\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/letter-box\"},{\"image\":\"assets\\/images\\/landing\\/application\\/5.jpg\",\"title\":\"To-Do\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/to-do\"},{\"image\":\"assets\\/images\\/landing\\/application\\/6.jpg\",\"title\":\"Job Search\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/job-cards-view\"},{\"image\":\"assets\\/images\\/landing\\/application\\/7.jpg\",\"title\":\"Ecommerce\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/products\"},{\"image\":\"assets\\/images\\/landing\\/application\\/8.jpg\",\"title\":\"Faq\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/faq\"},{\"image\":\"assets\\/images\\/landing\\/application\\/9.jpg\",\"title\":\"File Manager\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/file-manager\"},{\"image\":\"assets\\/images\\/landing\\/application\\/10.jpg\",\"title\":\"Project\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/project-list\"},{\"image\":\"assets\\/images\\/landing\\/application\\/11.jpg\",\"title\":\"Contacts\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/contacts\"},{\"image\":\"assets\\/images\\/landing\\/application\\/12.jpg\",\"title\":\"Chat\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/chat-private\"}]}},\"feature\":{\"laravel_feature\":{\"sub_title\":\"Why you choose Riho\",\"title\":\"Unique Laravel\",\"poster\":[{\"title\":\"Quality & Clean Code\",\"description\":\"All you need to know of using clean code as a manager to make your team and your software awesome.\",\"svg_icon\":\"frontend\\/images\\/landing\\/feature-icon\\/1.svg\"},{\"title\":\"Bootstrap v5.0\",\"description\":\"Powerful feature-packed frontend toolkit. Build and customize with Sass, utilize prebuilt grid system.\",\"svg_icon\":\"frontend\\/images\\/landing\\/feature-icon\\/2.svg\"},{\"title\":\"Handmade Icons\",\"description\":\"let\\u2019s learn how to svg icons in Riho admin template, handmade icons different materials.\",\"svg_icon\":\"frontend\\/images\\/landing\\/feature-icon\\/3.svg\"},{\"title\":\"Limitless Components\",\"description\":\"The limitless layout collection and UI kit biggest collection of layouts for web design.\",\"svg_icon\":\"frontend\\/images\\/landing\\/feature-icon\\/4.svg\"},{\"title\":\"Easy Customizable\",\"description\":\"Easy Step-By-Step Guide for Beginners.customize your layout, settings and content.\",\"svg_icon\":\"frontend\\/images\\/landing\\/feature-icon\\/5.svg\"},{\"title\":\"Responsive\",\"description\":\"Use Responsive Design to Connect with all Device designing your website for mobile devices.\",\"svg_icon\":\"frontend\\/images\\/landing\\/feature-icon\\/6.svg\"},{\"title\":\"Premium Support\",\"description\":\"We are always be their for your support and you are facing some issues you can create ticket.\",\"svg_icon\":\"frontend\\/images\\/landing\\/feature-icon\\/7.svg\"},{\"title\":\"Colors Options\",\"description\":\"Riho provide unlimited main color option.other colors you can change easily using scss variables.\",\"svg_icon\":\"frontend\\/images\\/landing\\/feature-icon\\/8.svg\"}]},\"premium_support\":{\"title\":\"we give it as we think that excellent support is needed\",\"description\":\"fast issue resolution, and dedicated experts for a seamless experience\",\"button_text\":\"Premium Support\",\"button_url\":\"https:\\/\\/support.pixelstrap.com\\/portal\\/en\\/signin\",\"left_side_title\":\"Our License\",\"main_image\":\"frontend\\/images\\/landing\\/unique.png\",\"right_side_title\":\"Premium Support\",\"marquee_title\":\"Premium Support. Our License . Premium Support .\"}},\"footer\":{\"logo\":\"frontend\\/images\\/logo\\/logo.png\",\"description\":\"Riho Globally Trusted Laravel Admin Theme\",\"copyright_text\":\"Copyright 2025 \\u00a9 Riho All rights reserved.\",\"left_button_text\":\"Check Now\",\"left_button_url\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/default-dashboard\",\"right_button_text\":\"Rate Us\",\"right_button_url\":\"https:\\/\\/themeforest.net\\/user\\/pixelstrap\\/portfolio\",\"middle_button_text\":\"Buy Now\",\"middle_button_url\":\"https:\\/\\/themeforest.net\\/user\\/pixelstrap\\/portfolio\"}}', '2025-06-09 00:44:05', '2025-06-09 00:44:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `landing_pages`
--
ALTER TABLE `landing_pages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `landing_pages`
--
ALTER TABLE `landing_pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;


INSERT INTO `modules` (`id`, `name`, `actions`, `created_at`, `updated_at`, `deleted_at`) VALUES
(8, 'settings', '{\"index\":\"setting.index\",\"edit\":\"setting.edit\"}', Null, Null, NULL),
(9, 'landingPage', '{\"index\":\"landingPage.index\",\"edit\":\"landingPage.edit\"}', Null, Null, NULL);

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(36, 'setting.index', 'web', NULL, NULL),
(37, 'setting.edit', 'web', NULL, NULL),
(38, 'landingPage.index', 'web', NULL, NULL),
(39, 'landingPage.edit', 'web', NULL, NULL);


INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(36, 1),
(37, 1),
(38, 1),
(39, 1);

