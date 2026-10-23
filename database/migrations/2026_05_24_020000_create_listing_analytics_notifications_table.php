<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListingAnalyticsNotificationsTable extends Migration
{
    public function up()
    {
        Schema::create('listing_analytics_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('listing_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->date('period_start')->index();
            $table->date('period_end')->index();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->unique(['listing_id', 'period_start', 'period_end'], 'listing_analytics_notification_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('listing_analytics_notifications');
    }
}
