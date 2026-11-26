<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Indexes for the filters and sorts every public page runs. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', fn (Blueprint $t) => $t->index(['published_at', 'topic']));
        Schema::table('places', fn (Blueprint $t) => $t->index(['is_featured', 'sort']));
        Schema::table('events', fn (Blueprint $t) => $t->index('starts_at'));
        Schema::table('donations', fn (Blueprint $t) => $t->index(['status', 'confirmed_at']));
        Schema::table('businesses', fn (Blueprint $t) => $t->index(['category_id', 'status']));
    }

    public function down(): void
    {
        Schema::table('articles', fn (Blueprint $t) => $t->dropIndex(['published_at', 'topic']));
        Schema::table('places', fn (Blueprint $t) => $t->dropIndex(['is_featured', 'sort']));
        Schema::table('events', fn (Blueprint $t) => $t->dropIndex(['starts_at']));
        Schema::table('donations', fn (Blueprint $t) => $t->dropIndex(['status', 'confirmed_at']));
        Schema::table('businesses', fn (Blueprint $t) => $t->dropIndex(['category_id', 'status']));
    }
};
