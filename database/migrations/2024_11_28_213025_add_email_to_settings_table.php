<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert the new setting for email
        DB::table('settings')->insert([
            'key' => 'email',
            'value' => 'kctech365@gmail.com',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the email setting
        DB::table('settings')->where('key', 'email')->delete();
    }
};
