<?php

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Room;
use function Laravel\Prompts\table;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table){
            $table->id('RoomId');
            $table->string('RoomType', 255);
            $table->integer('RoomNumber');
            $table->integer('Capacity');
            $table->decimal('RoomPrice', 10, 2);
            $table->string('Status', 255);
            $table->longText('Description');
        });
        Schema::create('roompictures', function(Blueprint $table){
            $table->id('RoomPictureId');
            $table->foreignId('RoomId');
            $table->longText('PictureFile')->charset('binary');
        });



        Schema::create('guests', function(Blueprint $table){
            $table->id('GuestId');
            $table->string('FirstName', 255);
            $table->string('LastName', 255);
            $table->string('MiddleName', 255)->nullable();
            $table->string('Street', 255);
            $table->string('Brgy', 255);
            $table->string('City', 255);
            $table->string('Province', 255);
            $table->date('Birthdate');
            $table->string('Gender', 255);
            $table->string('ContactNumber', 12);
            $table->string('EmailAddress', 255);
            $table->string('Password', 255);
            $table->date('DateCreated');
            $table->time('TimeCreated');
        });

        Schema::create('employees', function(Blueprint $table){
            $table->id('EmployeeId');
            $table->string('FirstName', 255);
            $table->string('LastName', 255);
            $table->string('MiddleName', 255)->nullable();
            $table->string('Position', 32);
            $table->string('Status', 12);
            $table->string('ContactNumber', 12);
            $table->string('Gender', 255);
            $table->date('Birthdate');
            $table->string('Street', 255);
            $table->string('Brgy', 255);
            $table->string('City', 255);
            $table->string('Province', 255);
            $table->string('email', 255);
            $table->string('Username', 255);
            $table->string('password', 255);
            $table->date('DateCreated');
            $table->time('TimeCreated');
        });

        Schema::create('reservations', function(Blueprint $table){
            $table->id('ReservationId');
            $table->foreignId('GuestId');
            $table->foreignId('RoomId');
            $table->date('DateCreated');
            $table->time('TimeCreated');
            $table->date('DateCheckIn');
            $table->date('DateCheckOut');
            $table->decimal('TotalCost', 10, 2);;
            $table->decimal('Balance', 10, 2);
            $table->string('Status', 64);
        });

        Schema::create('amenities', function(Blueprint $table){
            $table->id('AmenitiesId');
            $table->string('Name');
            $table->integer('Price');
        });

        Schema::create('reservationamenities', function(Blueprint $table){
            $table->id('ReservationAmenitiesId');
            $table->foreignId('ReservationId');
            $table->foreignId('AmenitiesId');
            $table->integer('Quantity');
            $table->decimal('TotalCost', 10, 2);
        });

        Schema::create('payments', function(Blueprint $table){
            $table->id('PaymentId');
            $table->foreignId('GuestId');
            $table->foreignId('ReservationId');
            $table->decimal('AmountPaid', 10, 2);
            $table->date('DateCreated');
            $table->time('TimeCreated');
            $table->string('Status', 64);
            $table->string('PaymentType', 64);
            $table->string('ReferenceNumber', 64);
            $table->string('Purpose', 255);
        });

        Schema::create('promotions', function(Blueprint $table){
            $table->id('PromotionId');
            $table->string('Promotion', 255);
            $table->longText('Description');
            $table->integer('Discount');
            $table->date('StartDate');
            $table->date('EndDate');
            $table->date('DateCreated');
        });

        Schema::create('discountedrooms', function(Blueprint $table){
            $table->id('DiscountedRoomId');
            $table->foreignId('RoomId');
            $table->foreignId('PromotionId');
        });

        Schema::create('checkinouts', function(Blueprint $table){
            $table->id('CheckInOutId');
            $table->foreignId('ReservationId');
            $table->foreignId('GuestId');
            $table->date('DateCreated');
            $table->time('TimeCreated');
            $table->string('Type', 12);
        });
    }

    public function down(): void
    {
        Schema::drop('rooms');
        Schema::drop('roompictures');
        Schema::drop('useraccounts');
        Schema::drop('guests');
        Schema::drop('reservations');
        Schema::drop('amenities');
        Schema::drop('reservationamenities');
        Schema::drop('payments');
        Schema::drop('promotions');
    }
};
