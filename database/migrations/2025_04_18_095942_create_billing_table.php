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
        Schema::create('billing', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id')->nullable(); // optional foreign key to sender
            $table->string('description');
            $table->integer('quantity')->default(1);
            $table->decimal('rate', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
    
            // Foreign key constraint (optional)
            $table->foreign('sender_id')->references('id')->on('senders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billing');
    }
};
