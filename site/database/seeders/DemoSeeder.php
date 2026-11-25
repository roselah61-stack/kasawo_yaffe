<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Category;
use App\Models\Donation;
use App\Models\Event;
use App\Models\Notice;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * SAMPLE data so the site looks alive during development.
 * These businesses, supporters and events are fictional. Do not run in production:
 *   php artisan migrate:fresh --seed --seeder=ContentSeeder
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // Local test admin only (never seeded in production). Create real admins with: php artisan kasawo:admin
        User::updateOrCreate(['email' => 'admin@kasawo.test'], ['name' => 'Test Admin', 'password' => 'kasawo-local-admin', 'is_admin' => true]);

        $cat = Category::pluck('id', 'slug');
        $hours = ['Mon – Fri' => '8:00 – 19:00', 'Saturday' => '8:00 – 17:00', 'Sunday' => 'Closed'];
        $phone = '+256 700 000 000'; // placeholder

        $businesses = [
            ['Nakato Fashions', 'fashion-tailoring', 'Gomesi, kanzu, school uniforms & alterations.', 'images/tailor.jpg', 4.9, 38, true, true, 'both',
                'We have made gomesi, kanzu, school uniforms and everyday wear for families in Kasawo for over 10 years. We take orders for weddings, kwanjula and graduations, and do alterations while you wait.',
                [['Gomesi (made to measure)', 'Ready in 5–7 days', 150000, null, 'images/culture-intro.jpg'], ['School uniform set', 'Shirt / dress + shorts / skirt', 45000, null, null], ['Kanzu', 'Classic white, all ages', 80000, null, 'images/culture-intro.jpg'], ['Alterations', 'While you wait', 5000, 'from', 'images/market.jpg']]],
            ['Kyaggwe Fresh Pineapples', 'farm-produce', 'Wholesale & retail pineapples, delivered.', 'images/pineapple.jpg', 4.6, 21, true, true, 'products',
                'Sweet, farm-fresh pineapples, wholesale and retail. We deliver to Mukono and Kampala every Tuesday and Friday. Bulk orders welcome for juice makers, hotels and schools.',
                [['Pineapples, large (per piece)', 'Graded, unmarked', 3000, null, null], ['Pineapples, bulk (per 100)', 'Delivered within Mukono', 220000, null, null]]],
            ['Safe Ride Boda Stage', 'transport', 'Registered riders, helmets for every passenger.', 'images/boda.jpg', 4.8, 54, true, false, 'services',
                'Registered riders with helmets for every passenger. Trips across the sub-county, deliveries and parcel runs to Mukono.',
                [['Trip within the trading centre', null, 2000, 'from', null], ['Delivery to Mukono town', null, 25000, 'from', null]]],
            ['Mama Luwombo Kitchen', 'food-drinks', 'Local dishes & catering for weddings and kwanjula.', 'images/food-leaves.jpg', 4.9, 47, true, true, 'both',
                'Traditional Buganda food cooked the slow way, steamed in banana leaves. Daily lunch, plus catering for weddings, kwanjula, burials and church functions.',
                [['Chicken luwombo', 'Serves one', 15000, null, null], ['Catering', 'Per guest, minimum 50', 12000, 'from', null]]],
            ['Kasawo Crafts Collective', 'crafts', 'Hand-woven baskets, mats & gift hampers.', 'images/baskets.jpg', 4.5, 12, false, false, 'products',
                'A group of local women weaving baskets, mats and gift hampers from natural fibres. Perfect for kwanjula gifts and visitors’ souvenirs.',
                [['Gift basket with lid', 'Medium', 35000, null, null], ['Woven mat', 'Traditional pattern', 60000, null, null]]],
            ['Roadside Gonja Grill', 'food-drinks', 'Roasted gonja, chicken & fresh juice.', 'images/gonja.jpg', 4.7, 29, true, false, 'products',
                'Hot roasted gonja, chicken on the stick and fresh passion-fruit juice for travellers on the main road. Open early till late.',
                [['Roasted gonja', 'Two pieces', 1000, null, null], ['Fresh juice', '500 ml', 2000, null, null]]],
            ['Ssemanda Matooke Supply', 'farm-produce', 'Fresh bunches daily, bulk orders for schools.', 'images/matooke-bike.jpg', 4.4, 18, true, false, 'products',
                'Fresh matooke bunches daily, carefully handled and graded. Weekly bulk supply for schools, hotels and restaurants.',
                [['Matooke bunch, large', null, 35000, 'from', null]]],
            ['Green Hills Guesthouse', 'lodging', 'Clean rooms, hot showers & breakfast.', 'images/matooke-garden.jpg', 4.3, 9, false, false, 'services',
                'A quiet guesthouse a short walk from the trading centre. Clean self-contained rooms, hot showers and a local breakfast.',
                [['Single room', 'Per night, breakfast included', 50000, null, null]]],
            ['Sezibwa Heritage Guides', 'tours-guides', 'Licensed local guides for culture & nature tours.', 'images/sezibwa-top.jpg', 4.9, 31, true, true, 'services',
                'Local guides who know the history and legends of the area. Half-day and full-day tours to Sezibwa Falls, farms and cultural sites.',
                [['Half-day guided tour', 'Per group up to 6', 80000, null, null]]],
        ];

        foreach ($businesses as [$name, $slug, $tagline, $img, $rating, $reviews, $verified, $featured, $type, $desc, $products]) {
            $b = Business::updateOrCreate(['slug' => Str::slug($name)], [
                'category_id' => $cat[$slug], 'name' => $name, 'tagline' => $tagline, 'description' => $desc, 'type' => $type,
                'phone' => $phone, 'location' => 'Kasawo Trading Centre', 'image' => $img,
                'gallery' => [$img, 'images/market.jpg', 'images/culture-intro.jpg'],
                'opening_hours' => $hours, 'payment_methods' => 'Cash · MTN · Airtel', 'delivery' => 'Kasawo & Mukono',
                'rating' => $rating, 'reviews_count' => $reviews, 'is_verified' => $verified, 'is_featured' => $featured, 'status' => 'approved',
            ]);
            $b->products()->delete();
            foreach ($products as [$pName, $note, $price, $prefix, $pImg]) {
                $b->products()->create(['name' => $pName, 'note' => $note, 'price' => $price, 'price_prefix' => $prefix, 'image' => $pImg]);
            }
            $b->reviews()->delete();
            $b->reviews()->createMany([
                ['author' => 'Sarah N.', 'rating' => 5, 'body' => 'Great service and fair prices. Highly recommend!', 'created_at' => now()->subWeeks(2)],
                ['author' => 'John K.', 'rating' => 5, 'body' => 'Friendly, reliable and on time. Will come back.', 'created_at' => now()->subMonth()],
            ]);
        }

        Notice::query()->delete();
        Notice::insert([
            ['title' => 'Community clean-up day', 'detail' => 'Saturday, 8:00 AM · Trading centre. Everyone welcome.', 'icon' => '📢', 'is_urgent' => false, 'created_at' => now()->subDays(1), 'updated_at' => now()],
            ['title' => 'Free farmer training: coffee seedlings', 'detail' => 'Sub-county grounds · Register at the sub-county office', 'icon' => '🌱', 'is_urgent' => false, 'created_at' => now()->subDays(2), 'updated_at' => now()],
            ['title' => 'Hiring: 3 sales staff', 'detail' => 'Local hardware store · Apply in person', 'icon' => '💼', 'is_urgent' => false, 'created_at' => now()->subDays(3), 'updated_at' => now()],
            ['title' => 'Lost: brown goat near the primary school', 'detail' => 'Please call the LC1 office if found', 'icon' => '🐐', 'is_urgent' => true, 'created_at' => now()->subDays(1), 'updated_at' => now()],
        ]);

        Event::query()->delete();
        foreach ([
            ['Kasawo market day', 7, '07:00', 'Trading centre'],
            ['Youth football tournament', 14, '14:00', 'Sub-county grounds'],
            ['Christmas carols & community dinner', 25, '18:00', 'Church grounds'],
            ['Farmers’ expo: pineapple & coffee', 40, '09:00', 'Kasawo'],
        ] as [$title, $days, $time, $where]) {
            Event::create(['title' => $title, 'starts_at' => now()->addDays($days)->setTimeFromTimeString($time), 'location' => $where]);
        }

        Donation::query()->delete();
        Business::updateOrCreate(['slug' => 'kasawo-fresh-bakery'], [
            'category_id' => $cat['food-drinks'], 'name' => 'Kasawo Fresh Bakery (sample pending)',
            'tagline' => 'Bread and cakes baked every morning.', 'description' => 'A new bakery waiting for review: bread, cakes and mandazi baked every morning.',
            'type' => 'products', 'owner_name' => 'Sample Owner', 'phone' => $phone, 'location' => 'Near the market', 'status' => 'pending',
            'image' => 'images/market.jpg', 'gallery' => ['images/market.jpg'],
        ]);
        Donation::create(['name' => 'Sample Pending Donor', 'phone' => $phone, 'amount' => 75000, 'fund' => 'heritage', 'method' => 'mtn', 'status' => 'pending']);
        foreach ([
            ['Grace Namuli', 'individual', null, 100000, 'heritage', false],
            ['', 'company', 'Mukono Hardware Ltd (sample)', 2000000, 'enterprise', false],
            ['Peter Ssali', 'individual', null, 50000, 'tourism', false],
            ['Anonymous', 'individual', null, 500000, 'general', true],
            ['Rose Nakalema', 'individual', null, 250000, 'heritage', false],
            ['', 'company', 'Kyaggwe Coffee Co. (sample)', 1500000, 'tourism', false],
            ['David Mukasa', 'individual', null, 20000, 'enterprise', false],
        ] as $i => [$name, $type, $company, $amount, $fund, $anon]) {
            Donation::create([
                'name' => $name ?: 'Company contact', 'donor_type' => $type, 'company_name' => $company, 'amount' => $amount,
                'fund' => $fund, 'method' => $i % 2 ? 'mtn' : 'bank', 'is_anonymous' => $anon,
                'status' => 'confirmed', 'confirmed_at' => now()->subDays($i),
            ]);
        }
    }
}
