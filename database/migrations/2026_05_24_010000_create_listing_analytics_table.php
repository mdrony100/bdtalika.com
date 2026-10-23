<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListingAnalyticsTable extends Migration
{
    public function up()
    {
        Schema::create('listing_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('listing_id')->nullable()->index();
            $table->unsignedBigInteger('listing_owner_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('event_type', 40)->index();
            $table->string('visitor_hash', 64)->nullable()->index();
            $table->string('ip_address', 64)->nullable();
            $table->string('device', 100)->nullable();
            $table->string('browser', 100)->nullable();
            $table->string('operating_system', 100)->nullable();
            $table->date('event_date')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('listing_analytics');
    }
}
