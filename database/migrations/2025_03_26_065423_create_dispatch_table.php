<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispatch', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to 'senders' table
            $table->unsignedBigInteger('sender_id');
            
            // Enum field for status with 'pending' and 'dispatch'
            $table->enum('status', ['pending', 'dispatch'])->default('pending');
            
            // Varchar field for dispatch_by
            $table->string('dispatch_by');
            
            // Optional timestamp for when the dispatch happened
            $table->timestamp('dispatched_at')->nullable();

            // Timestamps for created_at and updated_at
            $table->timestamps();

            // Foreign key constraint linking to the 'senders' table
            $table->foreign('sender_id')->references('id')->on('senders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispatch');
    }
}
