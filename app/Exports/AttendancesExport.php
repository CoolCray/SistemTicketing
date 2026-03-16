<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendancesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Attendance::with(['transaction.customers', 'transaction.seats'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Waktu Scan Entri Database',
            'Nama Pemesan',
            'Email',
            'No HP',
            'Nomor Kursi',
            'Waktu Check In',
            'Waktu Check Out'
        ];
    }

    public function map($attendance): array
    {
        $checkIn = $attendance->attendance_time ? date('Y-m-d H:i:s', strtotime($attendance->attendance_time)) : '-';
        $checkOut = $attendance->leave_time ? date('Y-m-d H:i:s', strtotime($attendance->leave_time)) : '-';

        return [
            $attendance->created_at->format('Y-m-d H:i:s'),
            $attendance->transaction && $attendance->transaction->customers ? $attendance->transaction->customers->name : '-',
            $attendance->transaction && $attendance->transaction->customers ? $attendance->transaction->customers->email : '-',
            $attendance->transaction && $attendance->transaction->customers ? $attendance->transaction->customers->phone_number : '-',
            $attendance->transaction && $attendance->transaction->seats ? $attendance->transaction->seats->seat_number : '-',
            $checkIn,
            $checkOut
        ];
    }
}
