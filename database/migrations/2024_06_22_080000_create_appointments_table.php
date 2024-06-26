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

        Schema::create('booking_times', function (Blueprint $table) {
            $table->id();
            $table->string('AppointmentTime');
            $table->timestamps();
        });
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('AppointmentNumber', 10);
            $table->date('AppointmentDate');
            $table->unsignedBigInteger('AppointmentTime_id');
            $table->string('name');
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('doctor_id');
            $table->text('Message')->nullable();
            $table->timestamp('ApplyDate');
            $table->string('Remark')->nullable();
            $table->text('DocMsg')->nullable();
            $table->string('Status')->nullable();
            $table->timestamps();

            
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('doctor_id')->references('id')->on('doctors');
            $table->foreign('AppointmentTime_id')->references('id')->on('booking_times');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
        Schema::dropIfExists('booking_times');
    }
};
