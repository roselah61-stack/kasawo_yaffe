<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Category;
use App\Models\Donation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_public_pages_render(): void
    {
        foreach (['/', '/our-story', '/explore', '/explore?type=Food', '/marketplace', '/marketplace?q=pineapple',
            '/marketplace/nakato-fashions', '/list-your-business', '/community', '/community/safe-drinking-water-at-home', '/donate'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_marketplace_search_filters_results(): void
    {
        $this->get('/marketplace?q=pineapple')->assertSee('Kyaggwe Fresh Pineapples')->assertDontSee('Safe Ride Boda Stage');
    }

    public function test_business_submission_is_pending_until_approved(): void
    {
        Storage::fake('public');

        $this->post('/list-your-business', [
            'name' => 'Test Bakery',
            'category_id' => Category::first()->id,
            'type' => 'products',
            'description' => 'Fresh bread and cakes baked every morning in Kasawo.',
            'owner_name' => 'Test Owner',
            'phone' => '+256 700 000 000',
            'location' => 'Trading centre',
            'photos' => [UploadedFile::fake()->image('shop.jpg')],
        ])->assertRedirect('/marketplace');

        $business = Business::where('name', 'Test Bakery')->firstOrFail();
        $this->assertSame('pending', $business->status);
        Storage::disk('public')->assertExists($business->image);
        $this->get('/marketplace/'.$business->slug)->assertNotFound();
    }

    public function test_business_submission_validates(): void
    {
        $this->post('/list-your-business', ['name' => ''])->assertSessionHasErrors(['name', 'category_id', 'description', 'phone']);
    }

    public function test_donation_creates_pending_gift_and_shows_instructions(): void
    {
        $this->post('/donate', [
            'amount' => 'custom', 'custom_amount' => '75,000', 'frequency' => 'monthly', 'fund' => 'heritage',
            'method' => 'mtn', 'donor_type' => 'individual', 'name' => 'Test Donor', 'phone' => '+256 700 000 000',
        ])->assertRedirect();

        $d = Donation::where('name', 'Test Donor')->firstOrFail();
        $this->assertSame(75000, $d->amount);
        $this->assertSame('pending', $d->status);
        $this->assertStringStartsWith('KY-', $d->reference);

        $this->get('/donate/thank-you/'.$d->reference)->assertOk()->assertSee($d->reference)->assertSee('MTN Mobile Money');
    }

    public function test_pending_donations_do_not_count_towards_total(): void
    {
        $before = Donation::counted()->sum('amount');
        Donation::create(['name' => 'X', 'amount' => 999000, 'fund' => 'general', 'method' => 'bank']);
        $this->assertSame($before, Donation::counted()->sum('amount'));
    }

    public function test_donation_rules(): void
    {
        $base = ['frequency' => 'once', 'fund' => 'general', 'donor_type' => 'company', 'name' => 'A', 'method' => 'card'];
        $this->post('/donate', [...$base, 'amount' => 'custom', 'custom_amount' => '100'])
            ->assertSessionHasErrors(['company_name', 'email', 'custom_amount']);
    }
}
