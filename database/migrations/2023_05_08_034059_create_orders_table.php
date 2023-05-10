<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->string('status')->default('pending');
            $table->string('payment_id')->nullable();
            $table->decimal('total_price', 8, 2);
            $table->decimal('shipping_price', 8, 2)->nullable();
            $table->string('country')->default('Argentina');
            $table->string('city')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('address')->nullable();
            $table->string('telephone')->nullable();
            $table->string('tracking_number')->nullable();
            $table->datetime('delivery_date')->nullable();
            $table->string('comments')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
