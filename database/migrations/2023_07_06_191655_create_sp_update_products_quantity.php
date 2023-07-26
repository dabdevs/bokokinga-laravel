<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // DB::unprepared('
        //     CREATE TRIGGER sp_update_products_quantity AFTER UPDATE ON orders
        //         FOR EACH ROW
        //             BEGIN
        //                 IF NEW.payment_status = "approved" THEN
        //                     -- Declare variables
        //                     DECLARE productId INT;
        //                     DECLARE purchaseQuantity INT;

        //                     -- Get the purchase
        //                     SELECT product_id, quantity INTO productId, purchaseQuantity FROM order_items WHERE order_id = NEW.id;
                            
        //                     -- Get the product id and purchase quantity from the inserted row
        //                     -- SELECT NEW.id, NEW.quantity INTO productId, purchaseQuantity;
                            
        //                     -- Update the product quantity
        //                     UPDATE products
        //                     SET quantity = quantity - purchaseQuantity
        //                     WHERE id = productId;
        //                 END IF;
        //             END
        //     ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('sp_update_products_quantity');
    }
};
