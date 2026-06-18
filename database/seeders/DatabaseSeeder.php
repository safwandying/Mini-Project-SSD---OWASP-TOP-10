<?php
namespace Database\Seeders;
use App\Models\AuditLog;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users — password auto-hashed via 'hashed' cast on User model
        $admin = User::create([
            'name'      => 'Admin SecureShop',
            'email'     => 'admin@secureshop.com',
            'password'  => 'Admin@1234!',
            'role'      => 'admin',
            'is_active' => true,
        ]);

        $user = User::create([
            'name'      => 'Ahmad Razif',
            'email'     => 'user@secureshop.com',
            'password'  => 'User@1234!',
            'role'      => 'user',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Nurul Ain',
            'email'     => 'nurul@example.com',
            'password'  => 'User@1234!',
            'role'      => 'user',
            'is_active' => true,
        ]);

        // Products (40 items)
        $products = [
            ['name'=>'Samsung 65" 4K Smart TV','description'=>'Crystal UHD 4K display, HDR10+, built-in Alexa, 3x HDMI, WiFi 5. Perfect for home cinema.','price'=>2199.00,'original_price'=>2799.00,'stock'=>25,'sold_count'=>312,'rating'=>4.7,'category'=>'electronics'],
            ['name'=>'Sony WH-1000XM5 Headphones','description'=>'Industry-leading noise cancelling, 30hr battery, multipoint connect, speak-to-chat technology. Crystal clear calls.','price'=>1299.00,'original_price'=>1599.00,'stock'=>80,'sold_count'=>1205,'rating'=>4.9,'category'=>'electronics'],
            ['name'=>'Apple MacBook Air M3','description'=>'13-inch Liquid Retina display, M3 chip, 8GB RAM, 256GB SSD, 18-hour battery. Featherlight at 1.24kg.','price'=>5499.00,'original_price'=>5899.00,'stock'=>40,'sold_count'=>523,'rating'=>4.8,'category'=>'electronics'],
            ['name'=>'Logitech MX Master 3S Mouse','description'=>'8K DPI sensor, MagSpeed electromagnetic scroll, quiet clicks, USB-C. Works on glass. Multi-device.','price'=>399.00,'original_price'=>499.00,'stock'=>150,'sold_count'=>2341,'rating'=>4.8,'category'=>'electronics'],
            ['name'=>'Keychron K2 Pro Keyboard','description'=>'75% hot-swap mechanical keyboard, RGB backlit, Bluetooth 5.1 + USB-C, Mac/Windows compatible.','price'=>499.00,'original_price'=>599.00,'stock'=>90,'sold_count'=>876,'rating'=>4.6,'category'=>'electronics'],
            ['name'=>'ASUS ROG 27" 165Hz Monitor','description'=>'QHD IPS panel, 1ms response, HDR400, G-Sync compatible. Curved display for immersive gaming.','price'=>1599.00,'original_price'=>1899.00,'stock'=>35,'sold_count'=>432,'rating'=>4.7,'category'=>'electronics'],
            ['name'=>'iPhone 16 Pro 256GB','description'=>'A18 Pro chip, 48MP ProRAW triple camera system, titanium design, Action Button, USB-C fast charge.','price'=>5299.00,'original_price'=>null,'stock'=>60,'sold_count'=>789,'rating'=>4.8,'category'=>'phones'],
            ['name'=>'Samsung Galaxy S25 Ultra','description'=>'200MP camera, built-in S Pen, 12GB RAM, 512GB storage, 5000mAh battery, 45W super fast charge.','price'=>4599.00,'original_price'=>4999.00,'stock'=>55,'sold_count'=>654,'rating'=>4.7,'category'=>'phones'],
            ['name'=>'Xiaomi 14T Pro','description'=>'Leica professional optics, 144Hz AMOLED display, 5000mAh, 120W HyperCharge, IP68 rated, Dimensity 9300+.','price'=>2299.00,'original_price'=>2699.00,'stock'=>100,'sold_count'=>1122,'rating'=>4.6,'category'=>'phones'],
            ['name'=>'realme GT 6T','description'=>'Snapdragon 8s Gen 3 processor, 120Hz AMOLED, 5500mAh battery, 120W super charging. Best gaming phone under RM1k.','price'=>999.00,'original_price'=>1199.00,'stock'=>200,'sold_count'=>3421,'rating'=>4.5,'category'=>'phones'],
            ['name'=>'Zara Floral Midi Dress','description'=>'Elegant floral print midi dress, v-neck, puff sleeves, 100% viscose fabric. Available in sizes XS-XL.','price'=>189.00,'original_price'=>259.00,'stock'=>150,'sold_count'=>2341,'rating'=>4.4,'category'=>'fashion'],
            ['name'=>'H&M Oversized Blazer','description'=>'Relaxed-fit tailored blazer, notched lapels, functional pockets, polyester blend. Office or casual wear.','price'=>219.00,'original_price'=>299.00,'stock'=>120,'sold_count'=>1876,'rating'=>4.3,'category'=>'fashion'],
            ['name'=>'Uniqlo Ultra Light Down Jacket','description'=>'Packable puffer jacket, 90% down filling, water-repellent coating, packs neatly into its own pocket.','price'=>249.00,'original_price'=>299.00,'stock'=>200,'sold_count'=>4532,'rating'=>4.7,'category'=>'fashion'],
            ['name'=>"Levi's 511 Slim Jeans",'description'=>'Slim through hip and thigh, sits below waist, authentic 5-pocket styling. 99% cotton for comfort.','price'=>199.00,'original_price'=>249.00,'stock'=>300,'sold_count'=>5671,'rating'=>4.5,'category'=>'mens'],
            ['name'=>'Polo Ralph Lauren Classic Polo','description'=>'100% cotton pique polo shirt, iconic embroidered logo, ribbed collar and cuffs. Sizes S to 3XL.','price'=>249.00,'original_price'=>319.00,'stock'=>180,'sold_count'=>3241,'rating'=>4.6,'category'=>'mens'],
            ['name'=>'Nike Air Max 270','description'=>'React foam midsole with large Max Air unit in heel, breathable mesh upper. Multiple colorways available.','price'=>459.00,'original_price'=>559.00,'stock'=>120,'sold_count'=>4321,'rating'=>4.6,'category'=>'shoes'],
            ['name'=>'Adidas Ultraboost 23','description'=>'Primeknit+ upper, responsive Boost midsole, Continental rubber outsole for grip. Top-rated running shoe.','price'=>549.00,'original_price'=>649.00,'stock'=>100,'sold_count'=>3876,'rating'=>4.7,'category'=>'shoes'],
            ['name'=>'New Balance 574 Core','description'=>'Heritage running silhouette, ENCAP midsole technology, suede and mesh upper, iconic NB logo.','price'=>329.00,'original_price'=>399.00,'stock'=>150,'sold_count'=>2987,'rating'=>4.5,'category'=>'shoes'],
            ['name'=>'Skinceuticals C E Ferulic Serum','description'=>'Vitamin C and E combined with Ferulic acid. Antioxidant protection, visibly reduces fine lines. 30ml bottle.','price'=>589.00,'original_price'=>649.00,'stock'=>60,'sold_count'=>987,'rating'=>4.8,'category'=>'beauty'],
            ['name'=>'Dyson Airwrap Complete','description'=>'Multi-styler and dryer in one. Curl, wave, smooth, and dry. No extreme heat damage. Works on all hair types.','price'=>1999.00,'original_price'=>2299.00,'stock'=>45,'sold_count'=>1234,'rating'=>4.7,'category'=>'beauty'],
            ['name'=>'Laneige Lip Sleeping Mask Berry','description'=>'Berry-scented overnight lip treatment mask. Vitamin C complex and antioxidants. 20g jar.','price'=>79.00,'original_price'=>99.00,'stock'=>500,'sold_count'=>12341,'rating'=>4.8,'category'=>'beauty'],
            ['name'=>'Optimum Nutrition Gold Whey 2.27kg','description'=>'24g protein per serving, 5.5g BCAAs naturally occurring. Chocolate Fudge flavour. 74 servings per tub.','price'=>289.00,'original_price'=>339.00,'stock'=>200,'sold_count'=>5432,'rating'=>4.7,'category'=>'health'],
            ['name'=>'Fitbit Charge 6','description'=>'Continuous heart rate, sleep tracking, built-in GPS, Google Maps integration, 7-day battery life. Water resistant 50m.','price'=>799.00,'original_price'=>999.00,'stock'=>80,'sold_count'=>2341,'rating'=>4.5,'category'=>'health'],
            ['name'=>'Omron Bluetooth Blood Pressure Monitor','description'=>'Clinically validated accuracy, Bluetooth connectivity, stores 100 readings, IntelliSense inflation technology.','price'=>299.00,'original_price'=>369.00,'stock'=>150,'sold_count'=>3876,'rating'=>4.6,'category'=>'health'],
            ['name'=>'Dyson V15 Detect Cordless Vacuum','description'=>'Laser dust detection reveals microscopic particles, LCD screen shows counts, 60min runtime, HEPA filtration.','price'=>2499.00,'original_price'=>2999.00,'stock'=>40,'sold_count'=>876,'rating'=>4.8,'category'=>'home'],
            ['name'=>'Philips Hue Starter Kit 3-Bulb','description'=>'3x E27 smart LED bulbs plus Hue Bridge. 16 million colours, works with Alexa, Google, Siri. App controlled.','price'=>449.00,'original_price'=>549.00,'stock'=>100,'sold_count'=>2341,'rating'=>4.6,'category'=>'home'],
            ['name'=>'IKEA POÄNG Armchair','description'=>'Bent birch layer-glued frame with cushion. Durable, flexible and comfortable for everyday use. Easy to assemble.','price'=>499.00,'original_price'=>null,'stock'=>60,'sold_count'=>1234,'rating'=>4.4,'category'=>'furniture'],
            ['name'=>'Nescafe Gold Blend 200g','description'=>'Smooth and balanced premium instant coffee. Blend of Arabica and Robusta beans. Rich aroma in every cup.','price'=>29.90,'original_price'=>39.90,'stock'=>500,'sold_count'=>15432,'rating'=>4.7,'category'=>'groceries'],
            ['name'=>'Milo 1.5kg Family Tin','description'=>'Original malt and chocolate energy drink powder. High in calcium and vitamins B2, B3, B6 and B12. Family size.','price'=>54.90,'original_price'=>64.90,'stock'=>400,'sold_count'=>23412,'rating'=>4.8,'category'=>'groceries'],
            ['name'=>'LEGO Technic McLaren Formula E','description'=>'1,432 pieces. Authentic replica with movable parts, functioning suspension. Age 10+. Great display model.','price'=>549.00,'original_price'=>649.00,'stock'=>50,'sold_count'=>876,'rating'=>4.8,'category'=>'toys'],
            ['name'=>'Wilson Evolution Basketball','description'=>'Official size 7 and weight, moisture-wicking composite leather cover, cushion core carcass. Indoor use.','price'=>189.00,'original_price'=>229.00,'stock'=>120,'sold_count'=>2341,'rating'=>4.6,'category'=>'sports'],
            ['name'=>'Decathlon Yoga Mat 10mm','description'=>'Non-slip TPE foam material, alignment lines printed, carry strap included, 183×61cm size. Eco-friendly.','price'=>99.00,'original_price'=>139.00,'stock'=>300,'sold_count'=>8765,'rating'=>4.5,'category'=>'sports'],
            ['name'=>'Atomic Habits by James Clear','description'=>'Tiny changes, remarkable results. The proven framework for building good habits and breaking bad ones. Paperback.','price'=>59.90,'original_price'=>79.90,'stock'=>200,'sold_count'=>12341,'rating'=>4.9,'category'=>'books'],
            ['name'=>'Rich Dad Poor Dad','description'=>'What the rich teach their kids about money that the poor and middle class do not. Robert Kiyosaki. Paperback.','price'=>49.90,'original_price'=>64.90,'stock'=>250,'sold_count'=>18765,'rating'=>4.7,'category'=>'books'],
            ['name'=>'Viper 4K Dash Cam','description'=>'4K UHD front camera, 170-degree wide angle, night vision, G-sensor, parking mode, 32GB SD card included.','price'=>399.00,'original_price'=>499.00,'stock'=>80,'sold_count'=>1234,'rating'=>4.6,'category'=>'automotive'],
            ['name'=>'Royal Canin Adult Cat Food 4kg','description'=>'Tailored nutrition for adult cats aged 1 to 7 years. Supports optimal digestion, healthy skin and coat.','price'=>189.00,'original_price'=>219.00,'stock'=>150,'sold_count'=>3421,'rating'=>4.7,'category'=>'pets'],
            ['name'=>'Pedigree Adult Dog Food 8kg','description'=>'Complete and balanced nutrition, 25% less fat, supports healthy joints and bones. Beef and vegetables flavour.','price'=>149.00,'original_price'=>179.00,'stock'=>200,'sold_count'=>4532,'rating'=>4.6,'category'=>'pets'],
            ['name'=>'JBL Flip 6 Bluetooth Speaker','description'=>'IP67 waterproof and dustproof, 12-hour playtime, PartyBoost to pair multiple speakers, USB-C charging.','price'=>449.00,'original_price'=>549.00,'stock'=>120,'sold_count'=>3456,'rating'=>4.7,'category'=>'electronics'],
            ['name'=>'Anker 65W GaN Charger 3-Port','description'=>'3 ports (2 USB-C + 1 USB-A), folds flat for travel, simultaneously powers MacBook, phone and tablet.','price'=>149.00,'original_price'=>199.00,'stock'=>300,'sold_count'=>8765,'rating'=>4.8,'category'=>'electronics'],
            ['name'=>'SanDisk 1TB Portable SSD','description'=>'USB 3.2 Gen 2 speeds up to 1050MB/s read, IP55 dust and water resistant rating. Pocket-sized convenience.','price'=>399.00,'original_price'=>499.00,'stock'=>100,'sold_count'=>2341,'rating'=>4.7,'category'=>'electronics'],
        ];

        foreach ($products as $p) {
            Product::create(array_merge($p, ['is_active' => true]));
        }

        // Sample orders for user
        $order1 = Order::create([
            'user_id'          => $user->id,
            'total_amount'     => 6798.00,
            'status'           => 'delivered',
            'shipping_address' => 'No 12, Jalan Mawar, Taman Bahagia, 47500 Subang Jaya, Selangor',
            'phone'            => '0123456789',
            'payment_method'   => 'online_banking',
            'tracking_number'  => 'MY-SS-2025051001',
        ]);
        OrderItem::create(['order_id'=>$order1->id,'product_id'=>3,'quantity'=>1,'unit_price'=>5499.00]);
        OrderItem::create(['order_id'=>$order1->id,'product_id'=>4,'quantity'=>1,'unit_price'=>399.00]);
        OrderItem::create(['order_id'=>$order1->id,'product_id'=>5,'quantity'=>1,'unit_price'=>499.00]);
        OrderItem::create(['order_id'=>$order1->id,'product_id'=>28,'quantity'=>3,'unit_price'=>29.90]);

        $order2 = Order::create([
            'user_id'          => $user->id,
            'total_amount'     => 1299.00,
            'status'           => 'shipped',
            'shipping_address' => 'No 12, Jalan Mawar, Taman Bahagia, 47500 Subang Jaya, Selangor',
            'phone'            => '0123456789',
            'payment_method'   => 'credit_card',
            'tracking_number'  => 'MY-SS-2025052801',
        ]);
        OrderItem::create(['order_id'=>$order2->id,'product_id'=>2,'quantity'=>1,'unit_price'=>1299.00]);

        $order3 = Order::create([
            'user_id'          => $user->id,
            'total_amount'     => 1098.00,
            'status'           => 'processing',
            'shipping_address' => 'No 12, Jalan Mawar, Taman Bahagia, 47500 Subang Jaya, Selangor',
            'phone'            => '0123456789',
            'payment_method'   => 'ewallet',
        ]);
        OrderItem::create(['order_id'=>$order3->id,'product_id'=>16,'quantity'=>1,'unit_price'=>459.00]);
        OrderItem::create(['order_id'=>$order3->id,'product_id'=>17,'quantity'=>1,'unit_price'=>549.00]);

        // Sample cart for user (DB-persisted)
        Cart::create(['user_id'=>$user->id,'product_id'=>1,'quantity'=>1]);
        Cart::create(['user_id'=>$user->id,'product_id'=>7,'quantity'=>1]);

        // Audit logs
        $logs = [
            [$admin->id,'user_registered','Admin account created.','127.0.0.1'],
            [$user->id, 'user_registered','New user registered: user@secureshop.com','192.168.1.10'],
            [$user->id, 'login_success',  'User logged in: user@secureshop.com','192.168.1.10'],
            [null,      'login_failed',   'Failed login for email: hacker@evil.com','10.0.0.99'],
            [null,      'login_failed',   'Failed login for email: hacker@evil.com','10.0.0.99'],
            [null,      'login_rate_limited','Rate limit hit: 5 attempts for hacker@evil.com','10.0.0.99'],
            [$user->id, 'order_placed',   "Order #{$order1->id} placed. Total: RM6798.00",'192.168.1.10'],
            [null,      'unauthorized_access','Non-admin attempted admin/dashboard','172.16.0.5'],
            [$user->id, 'order_placed',   "Order #{$order2->id} placed. Total: RM1299.00",'192.168.1.10'],
            [$admin->id,'order_status_updated',"Order #{$order1->id} status changed to: delivered",'127.0.0.1'],
        ];
        foreach ($logs as [$uid,$event,$desc,$ip]) {
            AuditLog::create(['user_id'=>$uid,'event'=>$event,'description'=>$desc,'ip_address'=>$ip,'user_agent'=>'Mozilla/5.0']);
        }
    }
}
