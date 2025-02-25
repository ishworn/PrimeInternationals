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
        Schema::create('boxes', function (Blueprint $table) {
            $table->id();
            $table->string('box_number', 11);  // Remove global unique constraint
            $table->foreignId('sender_id')->constrained()->onDelete('cascade');
            $table->decimal('box_weight', 8, 2)->nullable(); // Add box_weight column with decimal type
            $table->timestamps(0);
        
            // Ensure (sender_id, box_number) combination is unique
            $table->unique(['sender_id', 'box_number']);
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('boxes');
    }

};
