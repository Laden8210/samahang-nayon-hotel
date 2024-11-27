<?php

use App\Models\Guest;
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
        Schema::table('subguest', function (Blueprint $table) {
            $table->foreignIdFor(Guest::class, 'GuestId')->nullable();
        });
    }


    public function down(): void
    {

    }
};
