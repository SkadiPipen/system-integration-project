<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisition_items', function (Blueprint $table) {
            $table->id('req_item_id');
            $table->foreignId('req_id')->constrained('requisitions', 'req_id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('prod_id')->constrained('products', 'prod_id')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('unit');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisition_items');
    }
};