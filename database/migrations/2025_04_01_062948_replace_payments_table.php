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
        Schema::dropIfExists('payments');


        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained()->onDelete('cascade');
            $table->decimal('bill_amount', 12, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'both']);
            $table->decimal('cash_amount', 12, 2)->nullable();
            $table->decimal('bank_amount', 12, 2)->nullable();
            $table->decimal('total_paid', 12, 2);
            $table->enum('status', ['completed', 'pending', 'partial',])->nullable();
            $table->dateTime('payment_date')->useCurrent();
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
        Schema::dropIfExists('payments');
    }
};
