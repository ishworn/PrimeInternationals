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
        Schema::create('receivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id'); // Ensure it's unsigned
            $table->foreign('sender_id')->references('id')->on('senders')->onDelete('cascade');
            $table->string('receiverName');
            $table->string('receiverPhone')->nullable();;
            $table->string('receiverEmail')->nullable();
            $table->string('receiverPostalcode')->nullable();;
            $table->string('receiverCountry')->nullable();;
            $table->text('receiverAddress')->nullable();;
            $table->timestamps(0);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('receivers');
    }
    
};
