<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReceiptExport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Reservation;
use PhpOffice\PhpSpreadsheet\Writer\Html;
use Illuminate\Support\Facades\Auth;
use App\Models\Guest;
use Illuminate\Support\Facades\Http;

class ReceiptController extends Controller
{
    public function index(Request $request)
    {

        $payment = Payment::where('ReferenceNumber', $request->view)->first();

        if ($payment != null) {
            $amountInWords = $this->convertNumberToWords($payment->AmountPaid);
            $pdf = Pdf::loadView('receipt.index', compact('payment', 'amountInWords'));

            return $pdf->stream($request->view . '.pdf');
        }

        return redirect()->route('index');
    }


    public function printTotalTransaction(Request $request)
    {

        $request->validate([
            "reservation_id" => 'required'
        ]);

        $reservation = Reservation::where("ReservationId", $request->reservation_id)->first();

        $customPaper = [0, 0, 65 * 2.83465, 200 * 2.83465];

        $pdf = Pdf::loadView("receipt.transaction", compact('reservation'));
        return $pdf->stream($reservation->ReservationId . '.pdf');
    }

    function convertNumberToWords($number)
    {
        $words = '';

        $units = [
            '',
            'one',
            'two',
            'three',
            'four',
            'five',
            'six',
            'seven',
            'eight',
            'nine',
            'ten',
            'eleven',
            'twelve',
            'thirteen',
            'fourteen',
            'fifteen',
            'sixteen',
            'seventeen',
            'eighteen',
            'nineteen'
        ];

        $tens = [
            '',
            '',
            'twenty',
            'thirty',
            'forty',
            'fifty',
            'sixty',
            'seventy',
            'eighty',
            'ninety'
        ];

        if ($number < 0) {
            $words = 'negative ';
            $number = abs($number);
        }

        if ($number < 20) {
            $words .= $units[$number];
        } elseif ($number < 100) {
            $words .= $tens[intval($number / 10)];
            if ($number % 10) {
                $words .= '-' . $units[$number % 10];
            }
        } elseif ($number < 1000) {
            $words .= $units[intval($number / 100)] . ' hundred';
            if ($number % 100) {
                $words .= ' and ' . $this->convertNumberToWords($number % 100);
            }
        } elseif ($number < 1000000) {
            $words .= $this->convertNumberToWords(intval($number / 1000)) . ' thousand';
            if ($number % 1000) {
                $words .= ' ' . $this->convertNumberToWords($number % 1000);
            }
        } else {
            $words .= $this->convertNumberToWords(intval($number / 1000000)) . ' million';
            if ($number % 1000000) {
                $words .= ' ' . $this->convertNumberToWords($number % 1000000);
            }
        }

        return trim($words);
    }

    public function printReceipt($id)
    {

        $spreadsheet = IOFactory::load(storage_path('app/template.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getDefaultStyle()->getFont()->setSize(10);

        $reservation = Reservation::find($id);


        $totalPayment = 0;


        $amenityRow = 19;

        $lenghtOfStay = Carbon::parse($reservation->DateCheckIn)->diffInDays(Carbon::parse($reservation->DateCheckOut));


        $sheet->setCellValue('B18', $reservation->roomNumber->room->RoomType);
        $sheet->setCellValue('G18', $lenghtOfStay);
        $sheet->setCellValue('K18', "₱" . $reservation->roomNumber->room->RoomPrice);
        $sheet->setCellValue('P18', "₱" . $reservation->roomNumber->room->RoomPrice * $lenghtOfStay);

        foreach ($reservation->reservationAmenities as $reservationAmenity) {

            $sheet->setCellValue("B$amenityRow", $reservationAmenity->amenity->Name);
            $sheet->setCellValue("G$amenityRow", $reservationAmenity->Quantity);
            $sheet->setCellValue("k$amenityRow", "₱" . $reservationAmenity->amenity->Price);
            $sheet->setCellValue("p$amenityRow", "₱" . $reservationAmenity->TotalCost);

            $amenityRow++;
        }

        foreach ($reservation->payments as $payment) {
            $totalPayment += $payment->AmountPaid;
        }


        $amountInWords = $this->convertNumberToWords($payment->AmountPaid);

        $sheet->setCellValue('O7',  Carbon::now()->toDateString());
        $sheet->setCellValue('f8',  "with address at " . $reservation->guest->Street . ', ' . $reservation->guest->Brgy . ', ' . $reservation->guest->City . ', ' . $reservation->guest->Province);
        $sheet->setCellValue('f7', "                 " . $reservation->guest->FirstName . ' ' . $reservation->guest->LastName);
        $employee = Auth::user();

        $sheet->setCellValue('O14', $employee->FirstName . ' ' . $employee->LastName);


        $sheet->setCellValue('O6', Carbon::now()->format('F j, Y'));


        $sheet->setCellValue('F10', ucwords($amountInWords));


        $sheet->setCellValue('F9', $totalPayment);
        $sheet->setCellValue('F14', $reservation->IdNumber ?? 'N/A');

        $writer = new Html($spreadsheet);


        header('Content-Type: text/html');
        header('Content-Disposition: inline; filename="receipts.html"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        ob_start();
        $writer->save('php://output');
        $htmlOutput = ob_get_clean();

        // Return HTML response
        return response($htmlOutput, 200)
            ->header('Content-Type', 'text/html');
    }


    public function success($reference)
    {
        $payment = Payment::where('ReferenceNumber', $reference)->first();
        $guest = Guest::find($payment->GuestId);

        $reservation = Reservation::where("ReservationId", $payment->ReservationId)->first();
        $reservation->Status = "Booked";
        $reservation->save();

        $message = "Dear {$guest->FirstName},\n\n" .
            "Your payment has been confirmed successfully!\n" .
            "Payment Reference: {$reference}\n" .
            "Amount Paid: {$payment->AmountPaid}\n" .
            "Payment Type: {$payment->PaymentType}\n" .
            "Date: " . now()->toDateString() . "\n" .
            "Status: Confirmed\n\n" .
            "Thank you for your payment! We look forward to serving you.";

        $response = Http::post('https://nasa-ph.com/api/send-sms', [
            'phone_number' => $guest->ContactNumber,
            'message' => $message
        ]);


        $payment->update(['Status' => 'Confirmed']);

        return view('receipt.success', compact('reference'));
    }

    public function failed($reference)
    {
        $payment = Payment::where('ReferenceNumber', $reference)->first();
        $payment->update(['Status' => 'Failed']);

        $guest = Guest::find($payment->GuestId);
        $message = "Dear {$guest->FirstName},\n\n" .
            "We regret to inform you that your payment has failed.\n" .
            "Payment Reference: {$reference}\n" .
            "Amount: {$payment->AmountPaid}\n" .
            "Payment Type: {$payment->PaymentType}\n" .
            "Date: " . now()->toDateString() . "\n" .
            "Status: Failed\n\n" .
            "Please try again or contact support for assistance.";

        $response = Http::post('https://nasa-ph.com/api/send-sms', [
            'phone_number' => $guest->ContactNumber,
            'message' => $message
        ]);

        return view('receipt.failed', compact('reference'));
    }
}
