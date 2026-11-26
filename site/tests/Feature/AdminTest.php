<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Business;
use App\Models\Donation;
use App\Models\Event;
use App\Models\Notice;
use App\Models\Place;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_guests_are_sent_to_login_and_non_admins_are_blocked(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
    }

    public function test_only_admins_can_sign_in(): void
    {
        $user = User::factory()->create(['email' => 'plain@kasawo.test', 'password' => 'secret-pass-123']);
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'secret-pass-123'])->assertSessionHasErrors('email');
        $this->assertGuest();

        $admin = User::factory()->create(['email' => 'boss@kasawo.test', 'password' => 'secret-pass-123', 'is_admin' => true]);
        $this->post('/admin/login', ['email' => $admin->email, 'password' => 'secret-pass-123'])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
        $this->assertNotNull($admin->fresh()->last_login_at);
    }

    public function test_every_admin_page_renders(): void
    {
        $this->actingAs($this->admin());
        $b = Business::first();
        foreach (['/admin', '/admin/businesses', '/admin/businesses?status=pending', "/admin/businesses/{$b->id}/edit",
            '/admin/donations', '/admin/donations?status=confirmed', '/admin/articles', '/admin/articles/create',
            '/admin/articles/'.Article::first()->id.'/edit', '/admin/places', '/admin/places/create',
            '/admin/places/'.Place::first()->id.'/edit', '/admin/events', '/admin/notices', '/admin/subscribers'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_approving_a_business_publishes_it(): void
    {
        $b = Business::where('status', 'pending')->firstOrFail();
        $this->get('/marketplace/'.$b->slug)->assertNotFound();

        $this->actingAs($this->admin())->patch("/admin/businesses/{$b->id}/status", ['status' => 'approved'])->assertRedirect();
        $this->get('/marketplace/'.$b->slug)->assertOk();

        $this->patch("/admin/businesses/{$b->id}/toggle", ['field' => 'is_featured']);
        $this->assertTrue($b->fresh()->is_featured);
    }

    public function test_names_from_the_public_are_escaped_in_admin_messages(): void
    {
        $b = Business::where('status', 'pending')->firstOrFail();
        $b->update(['name' => '<script>alert(1)</script>Shop']);

        $this->actingAs($this->admin())->from('/admin/businesses')
            ->patch("/admin/businesses/{$b->id}/status", ['status' => 'approved']);

        $this->get('/admin/businesses')
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;Shop', false);
    }

    public function test_editing_a_business_saves_details_products_and_photo(): void
    {
        Storage::fake('public');
        $b = Business::first();

        $this->actingAs($this->admin())->put("/admin/businesses/{$b->id}", [
            'name' => 'Renamed Shop', 'category_id' => $b->category_id, 'type' => 'both', 'status' => 'approved',
            'is_verified' => '1', 'image' => UploadedFile::fake()->image('new.png'),
            'products' => [['name' => 'Item A', 'price' => 1000], ['name' => '', 'price' => '']],
        ])->assertRedirect();

        $b->refresh();
        $this->assertSame('Renamed Shop', $b->name);
        $this->assertFalse($b->is_featured);
        $this->assertSame(['Item A'], $b->products->pluck('name')->all());
        Storage::disk('public')->assertExists($b->image);
    }

    public function test_confirming_a_donation_counts_it(): void
    {
        $d = Donation::where('status', 'pending')->firstOrFail();
        $before = Donation::counted()->sum('amount');

        $this->actingAs($this->admin())->patch("/admin/donations/{$d->id}", ['status' => 'confirmed'])->assertRedirect();

        $this->assertNotNull($d->fresh()->confirmed_at);
        $this->assertSame($before + $d->amount, (int) Donation::counted()->sum('amount'));
    }

    public function test_donations_export_csv(): void
    {
        $res = $this->actingAs($this->admin())->get('/admin/donations/export?status=confirmed');
        $res->assertOk();
        $csv = $res->streamedContent();
        $this->assertStringContainsString('Reference,Date,Status', $csv);
        $this->assertStringNotContainsString(',pending,', $csv);
    }

    public function test_content_can_be_created_and_deleted(): void
    {
        Storage::fake('public');
        $this->actingAs($this->admin());

        $this->post('/admin/articles', [
            'title' => 'Draft tips', 'topic' => 'Farming', 'excerpt' => 'Short', 'body' => '## Hello', 'author' => 'Me', 'read_minutes' => 3,
            'image' => UploadedFile::fake()->image('a.png'),
        ])->assertRedirect('/admin/articles');
        $article = Article::where('title', 'Draft tips')->firstOrFail();
        $this->assertNull($article->published_at); // "published" switch was off
        $this->get('/community/'.$article->slug)->assertNotFound();

        $this->post('/admin/events', ['title' => 'Test meetup', 'starts_at' => now()->addWeek()->format('Y-m-d H:i'), 'location' => 'Hall'])->assertRedirect();
        $this->post('/admin/notices', ['title' => 'Water cut', 'detail' => 'Tuesday morning', 'is_urgent' => '1'])->assertRedirect();
        $this->assertTrue(Notice::where('title', 'Water cut')->value('is_urgent'));

        $event = Event::where('title', 'Test meetup')->firstOrFail();
        $this->delete("/admin/events/{$event->id}")->assertRedirect();
        $this->assertModelMissing($event);
    }
}
