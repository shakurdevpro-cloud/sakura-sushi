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
        Schema::dropIfExists('reservations');

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference', 20)->unique()->index();
            $table->enum('location', ['downtown', 'midtown']);
            $table->date('date')->index();
            $table->time('time');
            $table->unsignedTinyInteger('guests');
            $table->string('seating_preference', 50)->nullable();
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'no_show'])->default('pending')->index();
            $table->string('first_name', 80);
            $table->string('last_name', 80);
            $table->string('email', 180);
            $table->string('phone', 20);
            $table->text('special_requests')->nullable();
            $table->string('occasion', 100)->nullable();
            $table->boolean('sms_consent')->default(false);
            $table->boolean('sms_reminder_sent')->default(false);
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->timestamps();

            $table->index(['location', 'date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
