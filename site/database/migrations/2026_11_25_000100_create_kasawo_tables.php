<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon', 16)->nullable();
            $table->unsignedSmallInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->default('products'); // products | services | both
            $table->string('owner_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->json('opening_hours')->nullable();
            $table->string('payment_methods')->nullable();
            $table->string('delivery')->nullable();
            $table->decimal('rating', 2, 1)->default(0);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('pending'); // pending | approved | rejected
            $table->timestamps();
            $table->index(['status', 'is_featured']);
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('note')->nullable();
            $table->unsignedInteger('price')->nullable(); // UGX
            $table->string('price_prefix')->nullable(); // e.g. "from"
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('author');
            $table->unsignedTinyInteger('rating');
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type'); // Nature | Culture | Food | Farms
            $table->string('summary');
            $table->text('description')->nullable();
            $table->string('image');
            $table->string('duration')->nullable();
            $table->string('meta')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedSmallInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->dateTime('starts_at');
            $table->string('location');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('topic'); // Farming | Business | Health | Education | Safety | Digital
            $table->string('excerpt');
            $table->longText('body');
            $table->string('image');
            $table->string('author')->default('Kasawo Yaffe team');
            $table->unsignedSmallInteger('read_minutes')->default(4);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('detail');
            $table->string('icon', 16)->default('📢');
            $table->boolean('is_urgent')->default(false);
            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('donor_type')->default('individual'); // individual | company
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('amount'); // UGX
            $table->string('currency', 3)->default('UGX');
            $table->string('frequency')->default('once'); // once | monthly
            $table->string('fund'); // general | heritage | tourism | enterprise
            $table->string('method'); // mtn | airtel | card | bank
            $table->text('message')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('status')->default('pending'); // pending | confirmed | failed
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            $table->index('status');
        });

        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('contact')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['subscribers', 'donations', 'notices', 'articles', 'events', 'places', 'reviews', 'products', 'businesses', 'categories'] as $t) {
            Schema::dropIfExists($t);
        }
    }
};
