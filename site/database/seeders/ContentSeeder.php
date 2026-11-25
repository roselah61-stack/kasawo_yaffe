<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Place;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/** Real site content: categories, places and advice articles. Safe to run in production. */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['Farm produce', '🍍'], ['Food & drinks', '🍲'], ['Fashion & tailoring', '✂️'], ['Beauty & salons', '💇'],
            ['Transport', '🏍️'], ['Repairs & trades', '🔧'], ['Health', '🏥'], ['Education', '🎓'],
            ['Lodging', '🛏️'], ['Crafts', '🧺'], ['Tours & guides', '🧭'], ['Jobs & services', '💼'],
        ];
        foreach ($categories as $i => [$name, $icon]) {
            Category::updateOrCreate(['slug' => Str::slug(str_replace('&', '', $name))], ['name' => $name, 'icon' => $icon, 'sort' => $i]);
        }

        $places = [
            ['Sezibwa Falls', 'Nature', 'Sacred falls with a legend of twin rivers, cultural shrines and a forest trail.',
                'A sacred site in Buganda tradition, with a legend of twin rivers. Walk the forest trail, see the falls up close and learn about the shrines from local guides.',
                'images/hero-sezibwa.jpg', '2–3 hrs', '🎟 Entry fee', true],
            ['Rock pools & forest trail', 'Nature', 'A short, shaded walk to the rocks above the falls.', null, 'images/sezibwa-rocks.jpg', '1 hr', '👟 Easy', false],
            ['Pineapple farm walk', 'Farms', 'See how pineapples are grown and taste them straight from the field.', null, 'images/pineapple.jpg', '2 hrs', '👨‍👩‍👧 Family friendly', false],
            ['Cook luwombo with a local family', 'Food', 'Learn to wrap and steam a meal in banana leaves, then eat together.', null, 'images/food-leaves.jpg', '3 hrs', '🍲 Hands-on', false],
            ['Village homestay', 'Farms', 'Stay overnight with a family and join the day’s farm work.', null, 'images/matooke-garden.jpg', 'Overnight', '🏡 Homestay', false],
            ['Buganda heritage day trip', 'Culture', 'Combine a Kasawo visit with the Kasubi Tombs and Buganda’s royal sites near Kampala.', null, 'images/kasubi.jpg', 'Full day', '🏛 Heritage', false],
            ['Roadside food trail', 'Food', 'Roasted gonja, fresh fruit and snacks along the main road.', null, 'images/gonja.jpg', 'Any time', '💰 Cheap eats', false],
            ['Lake Victoria landing sites', 'Nature', 'Fishing boats, fresh tilapia and lake views on Mukono District’s shoreline.', null, 'images/lake.jpg', 'Half day', '🐟 Fresh fish', false],
            ['Kwanjula & culture experience', 'Culture', 'Learn the customs, dress and gifts of a Buganda introduction ceremony.', null, 'images/baskets.jpg', '2 hrs', '🎭 Culture', false],
        ];
        foreach ($places as $i => [$name, $type, $summary, $desc, $img, $dur, $meta, $featured]) {
            Place::updateOrCreate(['slug' => Str::slug($name)], [
                'name' => $name, 'type' => $type, 'summary' => $summary, 'description' => $desc, 'image' => $img,
                'duration' => $dur, 'meta' => $meta, 'is_featured' => $featured, 'sort' => $i,
            ]);
        }

        foreach ($this->articles() as $i => $a) {
            Article::updateOrCreate(['slug' => Str::slug($a['title'])], [
                ...$a,
                'published_at' => now()->subDays($i * 4 + 1),
            ]);
        }
    }

    private function articles(): array
    {
        return [
            [
                'title' => 'From farm to Kampala: selling pineapples for more',
                'topic' => 'Farming', 'image' => 'images/pineapple.jpg', 'read_minutes' => 8, 'is_featured' => true,
                'excerpt' => 'Grading, packing, finding buyers and using Mobile Money safely: a practical guide for pineapple farmers.',
                'body' => <<<'MD'
Pineapples from our area travel to Mukono, Kampala and beyond. The farmers who earn the most are usually the ones who **grade, pack and sell on purpose**, not the ones who sell everything at the farm gate on one day.

## 1. Grade before you sell

Sort your fruit into at least three groups:

- **Large and unmarked**: for supermarkets, hotels and juice makers who pay more.
- **Medium**: for market traders.
- **Small or marked**: for juice, home use or quick local sales.

Buyers pay more when they don’t have to sort for you.

## 2. Know the price before the buyer arrives

Ask two or three traders and neighbours what pineapples are going for this week. Write it down. A buyer who knows you have checked is less likely to offer a low price.

## 3. Sell together

Farmers who group their harvest can fill a whole truck and negotiate as one. Talk to your neighbours or your farmers’ group about a shared selling day.

## 4. Use Mobile Money safely

- Confirm money has **arrived in your account** before loading. Don’t rely on an SMS the buyer shows you.
- Never share your PIN, even with someone claiming to be from the network.
- Keep a simple record: date, buyer, quantity, price.

> A farmer who keeps records can show a bank or SACCO what they earn, and that opens the door to loans.

## 5. List your farm

Put your farm on the **Kasawo Yaffe Marketplace** with clear photos and your WhatsApp number. Buyers searching for fresh pineapples will find you directly.
MD,
            ],
            [
                'title' => 'Getting better prices for your matooke',
                'topic' => 'Farming', 'image' => 'images/matooke-bike.jpg', 'read_minutes' => 5,
                'excerpt' => 'When to sell, how to grade bunches and how to reach bigger markets.',
                'body' => <<<'MD'
Matooke prices rise and fall with the season. A little planning can add a lot to what you earn from each bunch.

## Watch the season

Prices are usually lower when everyone is harvesting at once. If you can, stagger your planting so some of your bunches are ready when supply is short.

## Grade your bunches

Big, full bunches with clean fingers sell for more. Keep them apart from smaller ones and price them separately.

## Handle with care

Bruised fingers ripen fast and lose value. Carry bunches carefully, keep them in the shade, and sell them soon after cutting.

## Find steady buyers

Schools, restaurants and hotels buy every week. A steady buyer at a fair price is often worth more than chasing the highest price on market day.
MD,
            ],
            [
                'title' => 'Registering your small business: a simple guide',
                'topic' => 'Business', 'image' => 'images/market.jpg', 'read_minutes' => 7,
                'excerpt' => 'The steps, the costs and what you need to bring.',
                'body' => <<<'MD'
Registering your business makes it easier to open a business bank account, win contracts and be trusted by bigger customers.

## Choose your business type

- **Business name**: simplest, for one person or a family trading under a name.
- **Company**: separates the business from you personally. Better if you plan to grow or bring in partners.

## Check and register

Business names and companies in Uganda are registered with the **Uganda Registration Services Bureau (URSB)**, which also has an online portal. Check that your name is available first.

## What to have ready

- Your National ID
- Your proposed business name (and one or two alternatives)
- Your business address and a contact phone number

## After registering

Ask about a **Tax Identification Number (TIN)** from URA, and check with the sub-county about any local trading licence.

> Fees and steps change from time to time. Always confirm current requirements with URSB or the sub-county office before you pay anyone.
MD,
            ],
            [
                'title' => 'Taking good product photos with your phone',
                'topic' => 'Digital', 'image' => 'images/tailor.jpg', 'read_minutes' => 4,
                'excerpt' => 'Simple tips that make your Marketplace listing stand out.',
                'body' => <<<'MD'
Customers decide in seconds. Good photos make them stop and call you.

## Use daylight

Take photos near a door or window, or outside in the shade. Avoid harsh midday sun and flash.

## Clean the background

A plain wall, a clean cloth or a mat makes your product stand out. Move clutter out of the picture.

## Show it being used

A dress being worn, food on a plate, a bike carrying a load. People want to imagine using what you sell.

## Take several, keep the best

Take five or six photos and choose the clearest. Wipe your camera lens first; a smudge makes everything look foggy.
MD,
            ],
            [
                'title' => 'Safe drinking water at home',
                'topic' => 'Health', 'image' => 'images/pot.jpg', 'read_minutes' => 3,
                'excerpt' => 'Boiling, storing and keeping water clean for your family.',
                'body' => <<<'MD'
Clean water prevents many common illnesses, especially for children.

## Treat it

Bring water to a **rolling boil** and let it cool covered. If you can’t boil, use water treatment tablets as directed.

## Store it safely

- Keep drinking water in a clean container with a lid.
- Pour or use a tap. Don’t dip cups or hands into the container.
- Wash the container regularly.

## Wash hands

Wash hands with soap before handling food and drinking water, and after using the latrine.

> If someone has diarrhoea for more than a day, or a child shows signs of dehydration, visit the nearest health centre.
MD,
            ],
            [
                'title' => 'Boda boda safety: what every rider and passenger should know',
                'topic' => 'Safety', 'image' => 'images/boda.jpg', 'read_minutes' => 4,
                'excerpt' => 'Helmets, registration and staying safe on the road.',
                'body' => <<<'MD'
Boda bodas keep Kasawo moving. A few habits keep riders and passengers alive.

## For passengers

- **Wear a helmet** every trip. Ask for one.
- Choose riders from a known, registered stage.
- Don’t ride with more than one other passenger.

## For riders

- Keep your bike serviced: brakes, lights and tyres.
- Never ride after drinking.
- Slow down near schools, markets and junctions.

## After an accident

Get the injured person to the nearest health centre quickly, and report the accident to the police.
MD,
            ],
        ];
    }
}
