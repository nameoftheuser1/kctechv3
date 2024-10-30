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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
        });

        // Insert default settings
        $currentYear = date('Y');
        DB::table('settings')->insert([
            [
                'key' => 'total_revenue_year',
                'value' => $currentYear,
            ],
            [
                'key' => 'total_expenses_year',
                'value' => $currentYear,
            ],
            [
                'key' => 'total_salaries_year',
                'value' => $currentYear,
            ],
            [
                'key' => 'predict_sales_month',
                'value' => 6,
            ],
            [
                'key' => 'historical_sales_data',
                'value' => 12,
            ],
            [
                'key' => 'predict_reservations_month',
                'value' => 6,
            ],
            [
                'key' => 'historical_reservations_data',
                'value' => 12,
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove all the settings inserted by this migration
        $keysToDelete = [
            'total_salaries_year',
            'total_revenue_date',
            'total_expenses_date',
            'predict_sales_month',
            'historical_sales_data',
            'predict_reservation_month',
            'historical_reservation_data',
        ];

        DB::table('settings')->whereIn('key', $keysToDelete)->delete();

        Schema::dropIfExists('settings');
    }
};
