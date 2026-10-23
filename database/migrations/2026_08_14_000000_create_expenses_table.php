<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type', 50)->default('other');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('BDT');
            $table->string('billing_cycle', 30)->default('one_time');
            $table->date('expense_date');
            $table->date('renewal_date')->nullable();
            $table->string('vendor')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['type', 'expense_date']);
            $table->index('renewal_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};
