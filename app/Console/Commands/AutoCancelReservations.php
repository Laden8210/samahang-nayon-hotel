<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class AutoCancelReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-cancel-reservations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredReservations = Reservation::where('Status', 'Unconfirmed Reservation')
            ->whereRaw('TIMESTAMP(DateCreated, TimeCreated) <= ?', [Carbon::now()->subHours(24)])
            ->get();

        foreach ($expiredReservations as $reservation) {
            // Update reservation status and cancellation date
            $reservation->update([
                'Status' => 'Cancelled',
                'DateCancelled' => Carbon::now()->toDateString(),
            ]);

            // Construct the notification message
            $message = "Dear {$reservation->guest->FirstName}, your reservation (ID: {$reservation->ReservationId}) has been automatically cancelled as it was not confirmed within 24 hours.";


            try {
                $response = Http::post('https://nasa-ph.com/api/send-sms', [
                    'phone_number' => $reservation->guest->ContactNumber,
                    'message' => $message
                ]);

                if ($response->failed()) {
                    $this->error("Failed to send SMS for reservation ID: {$reservation->ReservationId}");
                }
            } catch (\Exception $e) {
                $this->error("Error sending SMS for reservation ID: {$reservation->ReservationId}: " . $e->getMessage());
            }
        }

        $this->info(count($expiredReservations) . ' reservations were auto-cancelled.');

        return 0;
    }

}
