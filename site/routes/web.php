<?php

use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SubscriberController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/our-story', [PageController::class, 'story'])->name('story');
Route::get('/explore', [ExploreController::class, 'index'])->name('explore');

Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/list-your-business', [MarketplaceController::class, 'create'])->name('marketplace.create');
Route::post('/list-your-business', [MarketplaceController::class, 'store'])->middleware('throttle:5,1')->name('marketplace.store');
Route::get('/marketplace/{business}', [MarketplaceController::class, 'show'])->name('marketplace.show');

Route::get('/community', [CommunityController::class, 'index'])->name('community.index');
Route::get('/community/{article}', [CommunityController::class, 'show'])->name('community.show');

Route::get('/donate', [DonationController::class, 'create'])->name('donate.create');
Route::post('/donate', [DonationController::class, 'store'])->middleware('throttle:10,1')->name('donate.store');
Route::get('/donate/thank-you/{donation}', [DonationController::class, 'thanks'])->name('donate.thanks');

Route::post('/subscribe', [SubscriberController::class, 'store'])->middleware('throttle:5,1')->name('subscribe');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'create'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'store'])->middleware('throttle:6,1')->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'destroy'])->name('logout');
        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::get('businesses', [Admin\BusinessController::class, 'index'])->name('businesses.index');
        Route::get('businesses/{business:id}/edit', [Admin\BusinessController::class, 'edit'])->name('businesses.edit');
        Route::put('businesses/{business:id}', [Admin\BusinessController::class, 'update'])->name('businesses.update');
        Route::patch('businesses/{business:id}/status', [Admin\BusinessController::class, 'status'])->name('businesses.status');
        Route::patch('businesses/{business:id}/toggle', [Admin\BusinessController::class, 'toggle'])->name('businesses.toggle');
        Route::delete('businesses/{business:id}', [Admin\BusinessController::class, 'destroy'])->name('businesses.destroy');

        Route::get('donations', [Admin\DonationController::class, 'index'])->name('donations.index');
        Route::get('donations/export', [Admin\DonationController::class, 'export'])->name('donations.export');
        Route::patch('donations/{donation:id}', [Admin\DonationController::class, 'update'])->name('donations.update');

        Route::resource('articles', Admin\ArticleController::class)->except('show')->scoped(['article' => 'id']);
        Route::resource('places', Admin\PlaceController::class)->except('show')->scoped(['place' => 'id']);

        Route::get('events', [Admin\BoardController::class, 'events'])->name('events');
        Route::post('events', [Admin\BoardController::class, 'saveEvent'])->name('events.store');
        Route::put('events/{event}', [Admin\BoardController::class, 'saveEvent'])->name('events.update');
        Route::delete('events/{event}', [Admin\BoardController::class, 'destroyEvent'])->name('events.destroy');

        Route::get('notices', [Admin\BoardController::class, 'notices'])->name('notices');
        Route::post('notices', [Admin\BoardController::class, 'saveNotice'])->name('notices.store');
        Route::put('notices/{notice}', [Admin\BoardController::class, 'saveNotice'])->name('notices.update');
        Route::delete('notices/{notice}', [Admin\BoardController::class, 'destroyNotice'])->name('notices.destroy');

        Route::get('subscribers', [Admin\BoardController::class, 'subscribers'])->name('subscribers');
        Route::get('subscribers/export', [Admin\BoardController::class, 'exportSubscribers'])->name('subscribers.export');
        Route::delete('subscribers/{subscriber}', [Admin\BoardController::class, 'destroySubscriber'])->name('subscribers.destroy');
    });
});
