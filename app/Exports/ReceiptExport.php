<?php

namespace App\Exports;

use App\Models\Reservation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReceiptExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Reservation::all();
    }

    /**
     * Define the headings for the Excel sheet.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Invoice No.',
            'Amount',
            'Total Sales',
            'Less SCPWD Discount',
            'Total Due',
            'Less: Withholding Tax',
            'Payment Due',
            'Form of Payment',
            'Bank Name',
            'Cash',
            'Check',
            'Name',
            'Address at',
            'The sum of',
            'TIN',
            'Full payment of',
            'Sr. Citizen TIN',
            'OSCA/PWD ID No.',
            'Signature',
            'By',
            'Service/Amenities',
            'Quantity',
            'Unit Price',
            'Amount',
        ];
    }

    /**
     * Map the data from the Reservation model to the Excel sheet.
     *
     * @param mixed $reservation
     * @return array
     */
    public function map($reservation): array
    {
        return [
            $reservation->invoice_no,
            $reservation->amount,
            $reservation->total_sales,
            $reservation->less_scpwd_discount,
            $reservation->total_due,
            $reservation->less_withholding_tax,
            $reservation->payment_due,
            $reservation->form_of_payment,
            $reservation->bank_name,
            $reservation->cash,
            $reservation->check,
            $reservation->name,
            $reservation->address,
            $reservation->sum,
            $reservation->tin,
            $reservation->full_payment,
            $reservation->sr_citizen_tin,
            $reservation->osca_pwd_id_no,
            $reservation->signature,
            $reservation->by,
            $reservation->service_amenities,
            $reservation->quantity,
            $reservation->unit_price,
            $reservation->amount,
        ];
    }
}
