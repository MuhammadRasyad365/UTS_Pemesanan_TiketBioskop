
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->foreignId('screening_id')
                  ->constrained('screenings')
                  ->cascadeOnDelete();

            $table->string('booking_code')
                  ->unique();

            $table->string('customer_name');

            $table->string('customer_email');

            $table->string('customer_phone', 25)
                  ->nullable();

            $table->decimal('total_price', 10, 2);

            $table->string('status', 20)
                  ->default('confirmed');

            $table->timestamp('booked_at')
                  ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->dropForeign(['screening_id']);
            $table->dropUnique(['booking_code']);

            $table->dropColumn([
                'screening_id',
                'booking_code',
                'customer_name',
                'customer_email',
                'customer_phone',
                'total_price',
                'status',
                'booked_at',
            ]);
        });
    }
};
