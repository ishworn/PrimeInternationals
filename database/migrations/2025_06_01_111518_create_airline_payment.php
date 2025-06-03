<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airline_payment', function (Blueprint $table) {
            $table->id();

            // Foreign key to shipments table
            $table->unsignedBigInteger('shipment_id');
            $table->foreign('shipment_id')->references('id')->on('shipment')->onDelete('cascade');

            // Payment details
            $table->decimal('CustomClearence_payment', 10, 2)->default(0);
            $table->decimal('flight_charge', 10, 2)->default(0);
            $table->decimal('foreign_agencies', 10, 2)->default(0);
            $table->decimal('cash_amount', 10, 2)->default(0);
            $table->decimal('bank_amount', 10, 2)->default(0);
            $table->decimal('total_paid', 10, 2)->default(0);

            // ENUM for payment method
            $table->enum('payment_method', ['cash', 'bank', 'both'])->default('cash');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airline_payment');
    }
};
