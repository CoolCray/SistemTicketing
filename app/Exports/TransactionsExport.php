<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Load the same data as the index method
        $transactions = Transaction::with(['customers', 'packages', 'seats', 'transaction_additionals'])
            ->orderBy('created_at', 'desc')
            ->get();

        return $transactions->groupBy(function ($item) {
            return $item->customer_id . '_' . $item->package_id . '_' . $item->created_at->format('Y-m-d H:i');
        })->map(function ($group) {
            $first = $group->first();

            // Combine seats
            $seatNumbers = $group->map(function ($t) {
                return $t->seats ? $t->seats->seat_number : null;
            })->filter()->implode(', ');

            $first->setAttribute('grouped_seats', $seatNumbers);

            // Combine additionals
            $additionals = $group->flatMap(function ($t) {
                return $t->transaction_additionals;
            })->unique('id')->values();

            $first->setRelation('transaction_additionals', $additionals);

            // Calculate Price
            $packagePrice = $first->packages ? $first->packages->price : 0;
            $additionalPrice = $additionals->sum(function($a) {
                return $a->price * $a->pivot->jumlah;
            });
            $first->setAttribute('total_price', $packagePrice + $additionalPrice);

            // Record total seats in this group
            $first->setAttribute('total_seats', $group->count());

            return $first;
        })->values();
    }

    public function headings(): array
    {
        return [
            'Waktu Booking',
            'Sistem ID (First)',
            'Nama Pemesan',
            'Email Pemesan',
            'No HP',
            'Nama Siswa (Opsional)',
            'NIS (Opsional)',
            'Paket Dipilih',
            'Jumlah Kursi',
            'Nomor Kursi',
            'Additionals (Makanan/Minuman)',
            'Total Harga'
        ];
    }

    public function map($transaction): array
    {
        $additionals = '-';
        if ($transaction->transaction_additionals && $transaction->transaction_additionals->count() > 0) {
            $adds = [];
            foreach($transaction->transaction_additionals as $add) {
                $adds[] = $add->name . ' (' . $add->pivot->jumlah . ')';
            }
            $additionals = implode(', ', $adds);
        }

        return [
            $transaction->created_at->format('Y-m-d H:i:s'),
            $transaction->id,
            $transaction->customers ? $transaction->customers->name : '-',
            $transaction->customers ? $transaction->customers->email : '-',
            $transaction->customers ? $transaction->customers->phone_number : '-',
            $transaction->customers ? $transaction->customers->name_student : '-',
            $transaction->customers ? $transaction->customers->nis : '-',
            $transaction->packages ? $transaction->packages->name : '-',
            $transaction->total_seats,
            $transaction->grouped_seats,
            $additionals,
            $transaction->total_price ?? 0
        ];
    }
}
