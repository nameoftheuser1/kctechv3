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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('reference_number')->after('reservation_id'); // Adds the reference_number column
            $table->dropColumn('gcash_number'); // Removes the gcash_number column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('reference_number'); // Removes the reference_number column
            $table->string('gcash_number')->after('reservation_id'); // Restores the gcash_number column
        });
    }
};
