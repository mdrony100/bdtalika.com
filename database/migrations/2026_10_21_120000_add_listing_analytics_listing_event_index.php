<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Composite index so the customer analytics page (which filters
// listing_analytics by listing_id + event_type) stays fast as
// impression events multiply the row count.

return new class extends Migration
{
    public function up()
    {
        Schema::table('listing_analytics', function (Blueprint $table) {
            $table->index(['listing_id', 'event_type'], 'listing_analytics_listing_event_index');
        });
    }

    public function down()
    {
        Schema::table('listing_analytics', function (Blueprint $table) {
            $table->dropIndex('listing_analytics_listing_event_index');
        });
    }
};
