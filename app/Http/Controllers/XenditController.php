<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SamahangNayonMailer;
use App\Models\Reservation;
use Illuminate\Support\Facades\Http;
class XenditController extends Controller
{
    private $apiKey;
    public function index(){
        $paymentLink = route('online-payment', ['reservationId' => 1   ]);
        Mail::to('domingo.laden@gmail.com')->send(new SamahangNayonMailer($paymentLink));
        return json_encode(['message' => 'Email sent']);
    }
    public function createPayment(Request $request)
    {
        $this->apiKey = 'c2tfdGVzdF80OE1nWVk3U0dLdDY5dkVQZnRnZGpmS286';
        $data = [
            'data' => [
                'attributes' => [
                    'billing' => [
                        'name' => 'dsads',
                        'email' => 'dasd@gmail.com',
                        'phone' => '09123456789'
                    ],
                    'send_email_receipt' => true,
                    'show_description' => true,
                    'show_line_items' => true,
                    'description' => 'sdasd',
                    'line_items' => [
                        [
                            'currency' => 'PHP',
                            'amount' => 121,
                            'description' => '12dsd',
                            'name' => 'dsad',
                            'quantity' => 12
                        ],
                        [
                            'currency' => 'PHP',
                            'amount' => 123,
                            'description' => 'sdasa',
                            'quantity' => 32,
                            'name' => '234das'
                        ]
                    ],
                    'payment_method_types' => ['gcash'],
                    'reference_number' => 'dasddasd'
                ]
            ]
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . $this->apiKey,
            ])->post('https://api.paymongo.com/v1/checkout_sessions', $data);



            if ($response->successful()) {
                return response()->json($response->json());
            } else {

                return response()->json([
                    'error' => $response->body(),
                    'status' => $response->status()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
        // Get the reservation ID from the URL
    //     $reservationId = $request->route('reservationId');
    //     $reservation = Reservation::find($reservationId);



    //     $data = [
    //         'data' => [
    //             'attributes' => [
    //                 'line_items' => [
    //                     [
    //                         'currency'      => 'PHP',
    //                         'amount'        => $reservation->TotalCost * 100,
    //                         'description'   => "Payment for Room Reservation",
    //                         'name'          => $reservation->room->RoomType,
    //                         'quantity'      => 1,
    //                     ]
    //                 ],
    //                 'payment_method_types' => [
    //                     'gcash', // Available payment methods
    //                 ],
    //                 'success_url' => 'http://localhost:8000/success',
    //                 'cancel_url' => 'http://localhost:8000/cancel',
    //                 'description' => 'Payment for Sample Product'
    //             ]
    //         ]
    //     ];


    //     try {
    //         $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions')
    //                     ->withHeader('Content-Type: application/json')
    //                     ->withHeader('Authorization: Basic ' . base64_encode(env('PAYMONGO_SECRET_KEY') . ':'))
    //                     ->withData($data)
    //                     ->asJson()
    //                     ->post();

    //         return json_encode($response);

    //         Log::info('PayMongo API Response: ', (array)$response);

    //         if (isset($response->data->attributes->checkout_url)) {
    //             return redirect()->to($response->data->attributes->checkout_url);
    //         } else {

    //             Log::error('PayMongo API Error: No checkout_url found in response.');
    //             return redirect()->back()->with('error', 'Payment session creation failed.');
    //         }
    //     } catch (\Exception $e) {

    //         Log::error('PayMongo Error: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Payment session creation failed.');
    //     }
    // }
    }
}
