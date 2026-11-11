
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('screenings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('movie_id')
                  ->constrained('movies')
                  ->cascadeOnDelete();

            $table->string('studio_name', 50);
            $table->date('show_date');
            $table->time('start_time');
            $table->time('end_time')->nullable();

            $table->decimal('ticket_price', 10, 2);

            $table->string('status', 20)
                  ->default('available');

            $table->timestamps();

            $table->unique([
                'studio_name',
                'show_date',
                'start_time'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screenings');
    }
};
