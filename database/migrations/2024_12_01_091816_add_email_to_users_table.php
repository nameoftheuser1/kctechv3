<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add the email column to the users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->unique()->after('username');
        });

        // Insert the admin and user records with email, only if they don't exist already
        if (!DB::table('users')->where('username', 'admin')->exists()) {
            DB::table('users')->insert([
                'username' => 'admin',
                'email' => 'kctech365@gmail.com',
                'password' => Hash::make('admin'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!DB::table('users')->where('username', 'user')->exists()) {
            DB::table('users')->insert([
                'username' => 'user',
                'email' => 'user@example.com',
                'password' => Hash::make('user'),
                'role' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('email');
        });
    }
};
