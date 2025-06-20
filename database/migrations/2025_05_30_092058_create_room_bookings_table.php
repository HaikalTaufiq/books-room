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
        Schema::create('room_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('applicant_name'); // Nama pengaju
            $table->string('room_name'); // simpan nama ruangan langsung
            $table->string('activity_type'); // Jenis kegiatan
            $table->integer('capacity'); // Kapasitas pemakai
            $table->date('booking_date'); // Tanggal peminjaman
            $table->time('start_time'); // Jam mulai
            $table->time('end_time'); // Jam selesai
            $table->enum('status', ['approved', 'decline', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_bookings');
    }
};
