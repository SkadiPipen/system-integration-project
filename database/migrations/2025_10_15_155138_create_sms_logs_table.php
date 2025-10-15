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
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id('sms_id');
            $table->foreignId('req_id')
                  ->nullable()
                  ->constrained('requisitions')
                  ->onUpdate('cascade')
                  ->onDelete('set null');

            $table->foreignId('supplier_id')
                  ->nullable()
                  ->constrained('suppliers')
                  ->onUpdate('cascade')
                  ->onDelete('set null');

            $table->foreignId('employee_id')
                  ->nullable()
                  ->constrained('employees')
                  ->onUpdate('cascade')
                  ->onDelete('set null');

           
            $table->string('s_contact');
            $table->text('sms_message');
            $table->timestamp('sent_datetime')->nullable();
            $table->string('sms_status')->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
