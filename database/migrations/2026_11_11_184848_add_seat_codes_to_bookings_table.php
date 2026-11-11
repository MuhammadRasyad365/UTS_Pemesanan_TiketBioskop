
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->json('seat_codes')
                ->nullable()
                ->after('total_price');
        });

        // Menyalin kursi transaksi lama ke riwayat booking.
        DB::table('bookings')
            ->select('id')
            ->orderBy('id')
            ->chunkById(100, function ($bookings) {
                foreach ($bookings as $booking) {

                    $seatCodes = DB::table('booking_seats')
                        ->where('booking_id', $booking->id)
                        ->orderBy('seat_code')
                        ->pluck('seat_code')
                        ->all();

                    DB::table('bookings')
                        ->where('id', $booking->id)
                        ->update([
                            'seat_codes' => json_encode($seatCodes),
                        ]);
                }
            });
        // Membebaskan kursi dari transaksi lama yang sudah dibatalkan.
        DB::table('booking_seats')
            ->whereIn('booking_id', function ($query) {
                $query->select('id')
                    ->from('bookings')
                    ->where('status', 'cancelled');
            })
            ->delete();
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('seat_codes');
        });
    }
};
