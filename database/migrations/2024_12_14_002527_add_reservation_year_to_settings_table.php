<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddReservationYearToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add the reservationYear entry to the settings table
        DB::table('settings')->insert([
            'key' => 'reservation_year',
            'value' => now()->year,  // This will insert the current year
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rollback: Remove the reservationYear entry
        DB::table('settings')->where('key', 'reservation_year')->delete();
    }
}
