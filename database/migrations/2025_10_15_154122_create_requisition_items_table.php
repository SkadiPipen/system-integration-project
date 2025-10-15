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
        Schema::create('requisition_items', function (Blueprint $table) {
            $table->id('req_item_id');
            $table->foreignId('req_id')->constrained('requisitions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('prod_id')->constrained('products')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('qty_requested');
            $table->integer('qty_approved')->nullable();
            $table->text('req_remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_items');
    }
};
