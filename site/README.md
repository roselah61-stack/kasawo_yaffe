# Kasawo Yaffe

The online home of Kasawo, Mukono District: history, tourism, a local business Marketplace, community advice and donations.
Built with **Laravel 13 + Blade**, plain CSS and JavaScript bundled by Vite, and SQLite (switch to MySQL with `.env` when you go live).

## Run it locally

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate   # first time only
php artisan migrate:fresh --seed                    # content + sample data
php artisan storage:link
npm run build        # or `npm run dev` while editing CSS/JS
php artisan serve    # http://localhost:8000
```

Run the tests with `php artisan test`.

## Admin

The admin lives at **`/admin`**. Create an admin account (you'll be asked for a password):

```bash
php artisan kasawo:admin you@example.com --name="Your Name"
```

Run the same command again to reset a password. For local development, the demo seeder also creates a test admin. Its login details are in `database/seeders/DemoSeeder.php`, and it is never created in production.

The admin has:
- **Dashboard**: items waiting for review, money raised against the goal, gifts to confirm, and a monthly chart.
- **Businesses**: approve or reject new listings, mark them Verified or Featured, edit details, prices and photos, or delete.
- **Donations**: mark a gift **Received** once the money shows on your MoMo, Airtel or bank statement (only received gifts count), filter and search, and export to CSV.
- **Advice articles, Places to visit, Events, Notice board**: add, edit and remove.
- **Subscribers**: the newsletter list, with CSV export.

## Where things live

| What | Where |
|---|---|
| Pages | `resources/views/pages`, `marketplace`, `community`, `donate` |
| Header, footer, layout | `resources/views/layouts/app.blade.php`, `resources/views/partials` |
| Logo (Blade, with intro animation) | `resources/views/components/logo-mark.blade.php` |
| Logo files | `public/logo.svg`, `public/logo-mark.svg`, `public/favicon.svg` |
| Design system and animations | `resources/css/app.css` (colours at the top in `:root`) |
| Interactions (reveal on scroll, slideshow, counters, donate form) | `resources/js/app.js` |
| Donation funds, amounts, partner tiers, payment text | `config/kasawo.php` |
| Real content (categories, places, articles) | `database/seeders/ContentSeeder.php` |
| **Sample** businesses, supporters, events | `database/seeders/DemoSeeder.php` (not run in production) |

## Before going live

1. Set your real details in `.env`:
   ```
   KASAWO_PHONE="+256 ..."
   KASAWO_EMAIL=hello@yourdomain
   DONATE_MTN_MERCHANT=...
   DONATE_AIRTEL_MERCHANT=...
   DONATE_BANK_DETAILS="Bank, account name, account number, branch"
   DONATION_GOAL=25000000
   ```
2. Load content without sample data: `php artisan migrate:fresh --seed --seeder=ContentSeeder`.
3. Replace the stand-in photos in `public/images` with real Kasawo photos (credits for the current ones: `public/images/CREDITS.md`).
4. Have elders review the history on the Our Story page.

## How donations work today

A donor picks an amount, a fund and a payment method. The gift is saved as **pending** with a reference (e.g. `KY-AB12CD34`), and the donor sees payment steps for MoMo, Airtel, bank or card.
Only gifts marked **confirmed** count towards the progress bar and appear on the Supporters wall.
To take payments online automatically, connect a gateway such as Flutterwave or Pesapal at the hook marked in `DonationController@store`.

## Business listings

Submissions from "List your business" are saved as **pending** and don't appear until their `status` is set to `approved`.
