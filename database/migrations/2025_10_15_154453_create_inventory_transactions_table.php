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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id('trans_id');
            $table->foreignId('prod_id')->constrained('products', 'prod_id')->onUpdate('cascade')->onDelete('cascade');
            $table->string('trans_type');
            $table->string('trans_reference')->nullable();
            $table->integer('trans_qty');
            $table->date('trans_date');
            $table->text('trans_remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
