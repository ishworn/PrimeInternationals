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
        Schema::create('senders', function (Blueprint $table) {
            $table->id();
            $table->string('senderName');
            $table->string('senderPhone');
            $table->string('senderEmail')->unique();  // Unique constraint for senderEmail
            $table->text('senderAddress');
            $table->bigInteger('invoiceId')->nullable();
            $table->timestamps(0);  // Timestamps with no fractions
        });
    }

    public function down()
    {
        Schema::dropIfExists('senders');
    }

};
