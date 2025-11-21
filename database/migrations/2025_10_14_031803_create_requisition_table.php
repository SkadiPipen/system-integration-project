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
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id('req_id');
            $table->string('req_num')->unique();
            $table->foreignId('req_by')->constrained('employees', 'employee_id')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('supplier_id')
                  ->nullable()               
                  ->constrained('suppliers', 'supplier_id')  
                  ->onUpdate('cascade')     
                  ->onDelete('set null');   
            $table->date('request_date');
            $table->date('require_date');
            $table->text('remarks')->nullable();  
            $table->string('req_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};