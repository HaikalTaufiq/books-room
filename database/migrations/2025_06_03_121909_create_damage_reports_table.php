<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('damage_reports', function (Blueprint $table) {
            $table->id();
            $table->string('room');
            $table->string('damage_type');
            $table->date('found_date');
            $table->text('description')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('status')->default('Pending');
            $table->unsignedBigInteger('reported_by'); // relasi ke user
            $table->timestamps();

            $table->foreign('reported_by')->references('id')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('damage_reports');
    }
};
