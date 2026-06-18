-- ============================================================
-- SecureShop MySQL Database — FIXED VERSION
-- IKB21503 Secure Software Development | UniKL MIIT
--
-- HOW TO IMPORT:
--   phpMyAdmin → Create DB "secureshop" → Import this file
--   OR: mysql -u root secureshop < secureshop_database.sql
--
-- LOGIN CREDENTIALS:
--   Admin: admin@secureshop.com  / Admin@1234!
--   User:  user@secureshop.com   / User@1234!
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `carts`;
DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;

-- ── USERS ────────────────────────────────────────────────────
CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_index` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ⚠️  PASSWORDS: After importing this SQL, run this in terminal to set correct passwords:
--
--   php artisan tinker
--   User::find(1)->update(['password' => Hash::make('Admin@1234!')]);
--   User::find(2)->update(['password' => Hash::make('User@1234!')]);
--   User::find(3)->update(['password' => Hash::make('User@1234!')]);
--   exit
--
-- OR simply run: php artisan migrate:fresh --seed
-- (the seeder always creates correct hashes automatically)
--
-- These placeholder hashes below are for bcrypt('secret') — they WILL NOT WORK for login.
-- You MUST run the tinker commands above after import.
INSERT INTO `users` VALUES
(1,'Admin SecureShop','admin@secureshop.com','2025-01-01 00:00:00','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin',1,NULL,'2025-01-01 00:00:00','2025-01-01 00:00:00'),
(2,'Ahmad Razif','user@secureshop.com','2025-02-15 00:00:00','$2y$10$TKh8H1.PfunDj14wELNfKuRqZPWfoIkmoTWwhehMoDs5dhT9tYHZS','user',1,NULL,'2025-02-15 00:00:00','2025-02-15 00:00:00'),
(3,'Nurul Ain','nurul@example.com','2025-03-10 00:00:00','$2y$10$TKh8H1.PfunDj14wELNfKuRqZPWfoIkmoTWwhehMoDs5dhT9tYHZS','user',1,NULL,'2025-03-10 00:00:00','2025-03-10 00:00:00');

-- ── PASSWORD RESETS ───────────────────────────────────────────
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── SESSIONS ─────────────────────────────────────────────────
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── PRODUCTS ─────────────────────────────────────────────────
CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `stock` int UNSIGNED NOT NULL DEFAULT 0,
  `sold_count` int UNSIGNED NOT NULL DEFAULT 0,
  `rating` decimal(2,1) NOT NULL DEFAULT 4.5,
  `category` enum('electronics','phones','fashion','mens','shoes','beauty','health','groceries','food','home','furniture','toys','sports','books','automotive','pets','other') NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_active_category` (`is_active`,`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`name`,`description`,`price`,`original_price`,`stock`,`sold_count`,`rating`,`category`,`is_active`,`created_at`,`updated_at`) VALUES
-- ELECTRONICS
('Samsung 65" 4K Smart TV','Crystal UHD 4K display, HDR10+, built-in Alexa, 3x HDMI, WiFi 5. Perfect for home cinema.',2199.00,2799.00,25,312,4.7,'electronics',1,NOW(),NOW()),
('Sony WH-1000XM5 Headphones','Industry-leading noise cancelling, 30hr battery, multipoint connect, speak-to-chat. Crystal clear calls.',1299.00,1599.00,80,1205,4.9,'electronics',1,NOW(),NOW()),
('Apple MacBook Air M3','13-inch Liquid Retina, M3 chip, 8GB RAM, 256GB SSD, 18hr battery. Featherlight at 1.24kg.',5499.00,5899.00,40,523,4.8,'electronics',1,NOW(),NOW()),
('Logitech MX Master 3S Mouse','8K DPI sensor, MagSpeed scroll, quiet clicks, USB-C, works on glass. Multi-device.',399.00,499.00,150,2341,4.8,'electronics',1,NOW(),NOW()),
('Keychron K2 Pro Keyboard','75% hot-swap mechanical keyboard, RGB backlit, Bluetooth 5.1 + USB-C, Mac/Win compatible.',499.00,599.00,90,876,4.6,'electronics',1,NOW(),NOW()),
('ASUS ROG 27" 165Hz Monitor','QHD IPS, 1ms response, HDR400, G-Sync compatible, curved display for immersive gaming.',1599.00,1899.00,35,432,4.7,'electronics',1,NOW(),NOW()),
-- PHONES
('iPhone 16 Pro 256GB','A18 Pro chip, 48MP ProRAW camera system, titanium design, Action Button, USB-C.',5299.00,NULL,60,789,4.8,'phones',1,NOW(),NOW()),
('Samsung Galaxy S25 Ultra','200MP camera, S Pen, 12GB RAM, 512GB, 5000mAh battery, 45W fast charge.',4599.00,4999.00,55,654,4.7,'phones',1,NOW(),NOW()),
('Xiaomi 14T Pro','Leica optics, 144Hz AMOLED, 5000mAh, 120W HyperCharge, IP68, MediaTek Dimensity 9300+.',2299.00,2699.00,100,1122,4.6,'phones',1,NOW(),NOW()),
('realme GT 6T','Snapdragon 8s Gen 3, 120Hz AMOLED, 5500mAh, 120W charging, gaming phone under RM1k.',999.00,1199.00,200,3421,4.5,'phones',1,NOW(),NOW()),
-- FASHION
('Zara Floral Midi Dress','Elegant floral print midi dress, v-neck, puff sleeves, 100% viscose. Sizes XS-XL.',189.00,259.00,150,2341,4.4,'fashion',1,NOW(),NOW()),
('H&M Oversized Blazer','Relaxed-fit tailored blazer, notched lapels, functional pockets, polyester blend.',219.00,299.00,120,1876,4.3,'fashion',1,NOW(),NOW()),
('Uniqlo Ultra Light Down Jacket','Packable puffer jacket, 90% down filling, water-repellent, packs into pocket.',249.00,299.00,200,4532,4.7,'fashion',1,NOW(),NOW()),
-- MENS
('Levi\'s 511 Slim Jeans','Slim through hip and thigh, sits below waist, authentic 5-pocket styling. 99% cotton.',199.00,249.00,300,5671,4.5,'mens',1,NOW(),NOW()),
('Polo Ralph Lauren Classic Fit Polo','100% cotton pique, iconic embroidered logo, ribbed collar and cuffs. Sizes S-3XL.',249.00,319.00,180,3241,4.6,'mens',1,NOW(),NOW()),
-- SHOES
('Nike Air Max 270','React foam midsole, large Air unit heel, breathable mesh upper. Multiple colorways.',459.00,559.00,120,4321,4.6,'shoes',1,NOW(),NOW()),
('Adidas Ultraboost 23','Primeknit upper, Boost midsole, Continental rubber outsole. Top running shoe.',549.00,649.00,100,3876,4.7,'shoes',1,NOW(),NOW()),
('New Balance 574','Heritage running silhouette, ENCAP midsole, suede/mesh upper, iconic NB logo.',329.00,399.00,150,2987,4.5,'shoes',1,NOW(),NOW()),
-- BEAUTY
('Skinceuticals C E Ferulic Serum','Vitamin C+E with Ferulic acid. Antioxidant protection, reduces fine lines. 30ml.',589.00,649.00,60,987,4.8,'beauty',1,NOW(),NOW()),
('Dyson Airwrap Complete','Multi-styler and dryer. Curl, wave, smooth, dry. No extreme heat. All hair types.',1999.00,2299.00,45,1234,4.7,'beauty',1,NOW(),NOW()),
('Laneige Lip Sleeping Mask','Berry-scented overnight lip treatment. Vitamin C, antioxidants. 20g.',79.00,99.00,500,12341,4.8,'beauty',1,NOW(),NOW()),
-- HEALTH
('Optimum Nutrition Gold Whey 2.27kg','24g protein per serving, 5.5g BCAAs. Chocolate Fudge. 74 servings.',289.00,339.00,200,5432,4.7,'health',1,NOW(),NOW()),
('Fitbit Charge 6','Heart rate, sleep tracking, GPS, Google Maps, 7-day battery. Water resistant 50m.',799.00,999.00,80,2341,4.5,'health',1,NOW(),NOW()),
('Omron Blood Pressure Monitor','Clinically validated, Bluetooth, stores 100 readings, IntelliSense technology.',299.00,369.00,150,3876,4.6,'health',1,NOW(),NOW()),
-- HOME
('Dyson V15 Detect Vacuum','Laser dust detection, LCD screen, 60min runtime, HEPA filtration. Cordless.',2499.00,2999.00,40,876,4.8,'home',1,NOW(),NOW()),
('Philips Hue Starter Kit','3x E27 smart bulbs + Bridge. 16M colours, works with Alexa/Google. App controlled.',449.00,549.00,100,2341,4.6,'home',1,NOW(),NOW()),
('IKEA POÄNG Armchair','Bent birch frame with cushion. Layer-glued bent birch, durable and flexible.',499.00,NULL,60,1234,4.4,'furniture',1,NOW(),NOW()),
-- GROCERIES / FOOD
('Nescafe Gold Blend 200g','Smooth and balanced taste. Premium instant coffee. Made from Arabica & Robusta.',29.90,39.90,500,15432,4.7,'groceries',1,NOW(),NOW()),
('Milo 1.5kg Tin','Original malt chocolate energy drink powder. Family pack with free mug.',54.90,64.90,400,23412,4.8,'groceries',1,NOW(),NOW()),
-- TOYS & SPORTS
('LEGO Technic McLaren Formula E','1432 pieces. Authentic replica with movable parts. Age 10+. Display model.',549.00,649.00,50,876,4.8,'toys',1,NOW(),NOW()),
('Wilson Evolution Basketball','Official size 7, moisture-wicking composite leather, cushion core. Indoor.',189.00,229.00,120,2341,4.6,'sports',1,NOW(),NOW()),
('Decathlon Yoga Mat 10mm','Non-slip TPE foam, alignment lines, carry strap, 183×61cm. Eco-friendly.',99.00,139.00,300,8765,4.5,'sports',1,NOW(),NOW()),
-- BOOKS
('Atomic Habits by James Clear','Tiny changes, remarkable results. #1 NY Times bestseller. Paperback.',59.90,79.90,200,12341,4.9,'books',1,NOW(),NOW()),
('Rich Dad Poor Dad','What the rich teach their kids about money that the poor and middle class do not!',49.90,64.90,250,18765,4.7,'books',1,NOW(),NOW()),
-- AUTOMOTIVE
('Viper Car Dash Cam 4K','4K UHD front cam, 170° FOV, night vision, G-sensor, parking mode, 32GB SD.',399.00,499.00,80,1234,4.6,'automotive',1,NOW(),NOW()),
-- PETS
('Royal Canin Adult Cat Food 4kg','Tailored nutrition for adult cats 1-7 years. Optimal digestion, skin & coat.',189.00,219.00,150,3421,4.7,'pets',1,NOW(),NOW()),
('Pedigree Adult Dog Food 8kg','Complete nutrition, 25% less fat, supports healthy bones. Beef & vegetables.',149.00,179.00,200,4532,4.6,'pets',1,NOW(),NOW()),
-- PHONES extras
('JBL Flip 6 Bluetooth Speaker','IP67 waterproof, 12hr playtime, PartyBoost, USB-C. Bold JBL signature sound.',449.00,549.00,120,3456,4.7,'electronics',1,NOW(),NOW()),
('Anker 65W GaN Charger','3-port (2C+1A), folds flat, powers MacBook+phone+tablet simultaneously. Compact.',149.00,199.00,300,8765,4.8,'electronics',1,NOW(),NOW()),
('SanDisk 1TB Portable SSD','USB 3.2 Gen 2, 1050MB/s read, IP55 dust & water resistant. Pocket-sized.',399.00,499.00,100,2341,4.7,'electronics',1,NOW(),NOW());

-- ── CARTS ────────────────────────────────────────────────────
CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carts_user_product_unique` (`user_id`,`product_id`),
  KEY `carts_user_id` (`user_id`),
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sample cart items for user 2
INSERT INTO `carts` (`user_id`,`product_id`,`quantity`,`created_at`,`updated_at`) VALUES
(2,4,1,NOW(),NOW()),
(2,7,1,NOW(),NOW());

-- ── ORDERS ───────────────────────────────────────────────────
CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','shipped','delivered','completed','cancelled') NOT NULL DEFAULT 'pending',
  `shipping_address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `payment_method` varchar(30) DEFAULT NULL,
  `tracking_number` varchar(60) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_status` (`user_id`,`status`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `orders` (`id`,`user_id`,`total_amount`,`status`,`shipping_address`,`phone`,`payment_method`,`tracking_number`,`created_at`,`updated_at`) VALUES
(1001,2,6798.00,'delivered','No 12, Jalan Mawar, Taman Bahagia, 47500 Subang Jaya, Selangor','0123456789','online_banking','MY-SS-2025051001',NOW() - INTERVAL 30 DAY,NOW() - INTERVAL 25 DAY),
(1002,2,1299.00,'shipped','No 12, Jalan Mawar, Taman Bahagia, 47500 Subang Jaya, Selangor','0123456789','credit_card','MY-SS-2025052801',NOW() - INTERVAL 5 DAY,NOW() - INTERVAL 3 DAY),
(1003,2,1098.00,'processing','No 12, Jalan Mawar, Taman Bahagia, 47500 Subang Jaya, Selangor','0123456789','ewallet',NULL,NOW() - INTERVAL 1 DAY,NOW() - INTERVAL 1 DAY);

-- ── ORDER ITEMS ───────────────────────────────────────────────
CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id` (`order_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `order_items` (`order_id`,`product_id`,`quantity`,`unit_price`,`created_at`,`updated_at`) VALUES
(1001,3,1,5499.00,NOW() - INTERVAL 30 DAY,NOW() - INTERVAL 30 DAY),
(1001,7,1,5299.00,NOW() - INTERVAL 30 DAY,NOW() - INTERVAL 30 DAY),
(1002,2,1,1299.00,NOW() - INTERVAL 5 DAY,NOW() - INTERVAL 5 DAY),
(1003,16,1,459.00,NOW() - INTERVAL 1 DAY,NOW() - INTERVAL 1 DAY),
(1003,17,1,549.00,NOW() - INTERVAL 1 DAY,NOW() - INTERVAL 1 DAY);

-- ── AUDIT LOGS ───────────────────────────────────────────────
CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `event` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_logs_event_created` (`event`,`created_at`),
  KEY `audit_logs_user_created` (`user_id`,`created_at`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `audit_logs` (`user_id`,`event`,`description`,`ip_address`,`user_agent`,`created_at`,`updated_at`) VALUES
(1,'user_registered','Admin account created.','127.0.0.1','Seeder',NOW() - INTERVAL 90 DAY,NOW() - INTERVAL 90 DAY),
(2,'user_registered','New user registered: user@secureshop.com','192.168.1.10','Mozilla/5.0',NOW() - INTERVAL 60 DAY,NOW() - INTERVAL 60 DAY),
(2,'login_success','User logged in: user@secureshop.com','192.168.1.10','Mozilla/5.0',NOW() - INTERVAL 30 DAY,NOW() - INTERVAL 30 DAY),
(NULL,'login_failed','Failed login for email: hacker@evil.com','10.0.0.99','curl/7.68',NOW() - INTERVAL 10 DAY,NOW() - INTERVAL 10 DAY),
(NULL,'login_failed','Failed login for email: hacker@evil.com','10.0.0.99','curl/7.68',NOW() - INTERVAL 10 DAY,NOW() - INTERVAL 10 DAY),
(NULL,'login_rate_limited','Rate limit hit: 5 attempts for hacker@evil.com','10.0.0.99','curl/7.68',NOW() - INTERVAL 10 DAY,NOW() - INTERVAL 10 DAY),
(2,'order_placed','Order #1001 placed. Total: RM6798.00','192.168.1.10','Mozilla/5.0',NOW() - INTERVAL 30 DAY,NOW() - INTERVAL 30 DAY),
(NULL,'unauthorized_access','Non-admin attempted admin/dashboard','172.16.0.5','Mozilla/5.0',NOW() - INTERVAL 5 DAY,NOW() - INTERVAL 5 DAY),
(2,'order_placed','Order #1002 placed. Total: RM1299.00','192.168.1.10','Mozilla/5.0',NOW() - INTERVAL 5 DAY,NOW() - INTERVAL 5 DAY),
(1,'order_status_updated','Order #1001 status changed to: delivered','127.0.0.1','Mozilla/5.0',NOW() - INTERVAL 25 DAY,NOW() - INTERVAL 25 DAY);

-- ── CACHE ────────────────────────────────────────────────────
CREATE TABLE `cache` (`key` varchar(255) NOT NULL,`value` mediumtext NOT NULL,`expiration` int NOT NULL,PRIMARY KEY (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE `cache_locks` (`key` varchar(255) NOT NULL,`owner` varchar(255) NOT NULL,`expiration` int NOT NULL,PRIMARY KEY (`key`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- ── VERIFY ───────────────────────────────────────────────────
-- SELECT COUNT(*) FROM users;      -- 3
-- SELECT COUNT(*) FROM products;   -- 40
-- SELECT COUNT(*) FROM carts;      -- 2
-- SELECT COUNT(*) FROM orders;     -- 3
-- SELECT COUNT(*) FROM audit_logs; -- 10
