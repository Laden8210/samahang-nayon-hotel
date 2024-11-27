<?php

use App\Models\Reservation;
use App\Models\SubGuest;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id('MessageId');
            $table->foreignId('GuestId');
            $table->foreignId('EmployeeId')->nullable();
            $table->boolean('IsReadEmployee');
            $table->boolean('IsReadGuest');
            $table->longText('Message');
            $table->date('DateSent');
            $table->time('TimeSent');
        });

        Schema::create("subguest", function (Blueprint $table) {
            $table->id('SubGuestId');
            $table->string('FirstName', 255);
            $table->string('LastName', 255);
            $table->string('MiddleName', 255)->nullable();
            $table->date('Birthdate');
            $table->string('Gender', 255);
            $table->string('ContactNumber', 12);
        });

        Schema::create("subguestreservation", function (Blueprint $table) {
            $table->id('SubGuestReservationId');
            $table->foreignIdFor(Reservation::class, 'ReservationId');
            $table->foreignIdFor(SubGuest::class, 'SubGuestId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
