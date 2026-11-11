
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_seats', function (Blueprint $table) {

            $table->foreignId('booking_id')
                  ->constrained('bookings')
                  ->cascadeOnDelete();

            $table->foreignId('screening_id')
                  ->constrained('screenings')
                  ->cascadeOnDelete();

            $table->string('seat_code', 5);

            // Satu kursi hanya boleh dipesan satu kali
            // untuk satu jadwal penayangan.
            $table->unique([
                'screening_id',
                'seat_code'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('booking_seats', function (Blueprint $table) {

            $table->dropUnique([
                'screening_id',
                'seat_code'
            ]);

            $table->dropForeign(['booking_id']);
            $table->dropForeign(['screening_id']);

            $table->dropColumn([
                'booking_id',
                'screening_id',
                'seat_code',
            ]);
        });
    }
};
