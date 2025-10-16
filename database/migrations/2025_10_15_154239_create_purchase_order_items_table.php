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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id('po_item_id');
            $table->foreignId('po_id')->constrained('purchase_orders', 'po_id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('prod_id')->constrained('products', 'prod_id')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('qty_ordered');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('tot_price', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
