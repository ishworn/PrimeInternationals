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
        Schema::create('agencies_payment', function (Blueprint $table) {
            $table->id();

            // Foreign key to senders table
            $table->unsignedBigInteger('sender_id');
            $table->foreign('sender_id')->references('id')->on('senders')->onDelete('cascade');

            // Payment fields
            $table->decimal('cash_amount', 10, 2)->default(0);
            $table->decimal('bank_amount', 10, 2)->default(0);
            $table->decimal('total_paid', 10, 2)->default(0);

            // ENUM for payment method
            $table->enum('payment_method', ['cash', 'bank', 'both']);

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
        Schema::dropIfExists('agencies_payment');
    }
};
