<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('senders', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('senderPhone');
            $table->string('address1')->nullable()->after('company_name');
            $table->string('address2')->nullable()->after('address1');
            $table->string('address3')->nullable()->after('address2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('senders', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'address1', 'address2', 'address3']);
        });
    }
};
