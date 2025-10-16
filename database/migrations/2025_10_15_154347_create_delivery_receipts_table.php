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
        Schema::create('delivery_receipts', function (Blueprint $table) {
            $table->id('dr_id');
            $table->foreignId('po_id')->constrained('purchase_orders', 'po_id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('received_by')->constrained('employees','employee_id')->onUpdate('cascade')->onDelete('cascade');
            $table->date('delivery_date');
            $table->text('del_remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_receipts');
    }
};
