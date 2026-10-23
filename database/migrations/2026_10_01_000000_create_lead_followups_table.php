<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lead_followups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('listing_id')->unique();
            $table->enum('status', ['interested', 'not_interested', 'next_day_recall', 'paid'])->nullable();
            $table->text('comment')->nullable();
            $table->date('next_recall_date')->nullable();
            $table->timestamp('last_called_at')->nullable();
            $table->unsignedBigInteger('called_by_admin_id')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('lead_followups');
    }
};
