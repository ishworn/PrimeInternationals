<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('airline_payment', function (Blueprint $table) {
            // Drop foreign key first (you must know the constraint name or use Laravel's convention)
            $table->dropForeign(['shipment_id']); // Remove FK
            $table->dropColumn('shipment_id'); // Remove the column

            // Add JSON column
           
        });
    }

    public function down()
    {
        Schema::table('airline_payment', function (Blueprint $table) {
            // Reverse the above actions
           

            // Add back the shipment_id foreign key
            $table->unsignedBigInteger('shipment_id');
            $table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('cascade');
        });
    }
};
