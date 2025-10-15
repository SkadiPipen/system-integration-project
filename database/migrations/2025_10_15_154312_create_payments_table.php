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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('pay_id');
            $table->foreignId('po_id')->constrained('purchase_orders')->onUpdate('cascade')->onDelete('cascade');
            $table->date('pay_date');
            $table->decimal('pay_amount', 12, 2);
            $table->decimal('pay_due', 12, 2)->default(0);
            $table->string('pay_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
