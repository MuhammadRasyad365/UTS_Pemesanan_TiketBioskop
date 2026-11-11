<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();
            $table->string('genre', 100);
            $table->unsignedSmallInteger('duration');
            $table->string('age_rating', 10)->default('SU');

            $table->text('synopsis')->nullable();
            $table->string('poster_url')->nullable();

            $table->date('release_date')->nullable();
            $table->decimal('rating', 2, 1)->nullable();

            $table->decimal('ticket_price', 10, 2)
                  ->default(50000);

            $table->string('status', 20)
                  ->default('now_showing')
                  ->index();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
