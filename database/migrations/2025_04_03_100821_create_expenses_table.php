<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_name');
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->string('category');
            $table->string('other_category')->nullable();
            $table->enum('payment_method', ['Cash', 'Bank']);
            $table->string('receipt')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('expenses');
    }
};
