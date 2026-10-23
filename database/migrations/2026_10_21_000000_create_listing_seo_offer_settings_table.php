<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('listing_seo_offer_settings', function (Blueprint $table) {
            $table->id();
            $table->text('message_template')->nullable();
            $table->decimal('monthly_price', 10, 2)->default(1000);
            $table->decimal('yearly_price', 10, 2)->default(7000);
            $table->decimal('yearly_save', 10, 2)->default(3000);
            $table->string('contact_number', 30)->default('+8801738411242');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('listing_seo_offer_settings');
    }
};
