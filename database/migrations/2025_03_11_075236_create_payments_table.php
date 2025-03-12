<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->decimal('amount', 8, 2)->nullable(); // Decimal column for amount (8 digits total, 2 decimal places)
            $table->string('payment_method')->nullable(); // String column for payment method (nullable)
            $table->enum('status', ['paid', 'unpaid'])->nullable(); // Enum column for status (paid or unpaid, nullable)
            
            // Add foreign key for sender_id
            $table->unsignedBigInteger('sender_id')->nullable(); // Unsigned big integer for sender_id
            $table->foreign('sender_id')->references('id')->on('senders')->onDelete('cascade'); // Foreign key constraint

            $table->timestamps(); // Adds `created_at` and `updated_at` columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments'); // Drop the table if the migration is rolled back
    }
}