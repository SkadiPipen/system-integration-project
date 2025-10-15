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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id('po_id');
            $table->string('po_num')->unique();
            $table->foreignId('req_id')->nullable()->constrained('requisitions')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('supplier_id')->constrained('suppliers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('ordered_by')->constrained('employees')->onUpdate('cascade')->onDelete('cascade');
            $table->date('order_date');
            $table->string('po_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
