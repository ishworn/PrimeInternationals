<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('senders', function (Blueprint $table) {
            $table->id();  // Auto-incrementing 'id' field (Primary Key)
            $table->string('senderName');
            $table->string('senderPhone') ->nullable();;
            $table->string('senderEmail')->nullable(); // Unique constraint for senderEmail
            $table->text('senderAddress') ->nullable();;
            $table->text('trackingId')->nullable();  // Keeping trackingId as a nullable field for now
            $table->unsignedBigInteger('invoiceId')->nullable();  // Keeping invoiceId as a nullable field for now
            $table->timestamps(0);  // Timestamps with no fractions
        });

        // Set the starting value for the auto-increment field after table creation
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('senders');
    }
};
