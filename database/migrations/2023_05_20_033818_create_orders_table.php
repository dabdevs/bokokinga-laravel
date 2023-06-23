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
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->string('payment_status')->default('pending');
            $table->string('payment_id')->nullable();
            $table->decimal('subtotal', 8, 2);
            $table->decimal('shipping_price', 8, 2);
            $table->decimal('total_price', 8, 2);
            $table->unsignedBigInteger('address_id');
            $table->foreign('address_id')->references('id')->on('addresses');
            $table->string('comments')->nullable();
            $table->datetime('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->datetime('shipping_date')->nullable();
            $table->boolean('shipped')->default(false);
            $table->datetime('delivery_date')->nullable();
            $table->boolean('delivered')->default(false);
            $table->string('tracking_number')->nullable();
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
