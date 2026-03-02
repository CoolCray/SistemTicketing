<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeatsController extends Controller
{
    /**
     * Get all seats formatted for the UI grid.
     */
    public function index()
    {
        // Get all seats from the database
        $seats = DB::table('seats')->get();

        // Get all booked seat IDs from transactions (where they are actively booked)
        $bookedSeatIds = DB::table('transactions')->pluck('seat_id')->toArray();

        // Format the seats for the Vue UI
        $rows = ['A', 'B', 'C', 'D', 'E', 'F'];
        $layout = [];

        foreach ($rows as $rowLabel) {
            $rowSeats = $seats->filter(function ($seat) use ($rowLabel) {
                return str_starts_with($seat->seat_number, $rowLabel);
            })->values();

            // Assuming 10 seats per row (5 left, 5 right)
            $leftSeats = [];
            $rightSeats = [];

            foreach ($rowSeats as $index => $seat) {
                $status = in_array($seat->id, $bookedSeatIds) ? 'booked' : 'available';

                $seatData = [
                    'db_id'   => $seat->id,
                    'id'      => $seat->seat_number,
                    'label'   => $rowLabel,
                    'number'  => (int) substr($seat->seat_number, 1),
                    'status'  => $status
                ];

                if ($index < 5) {
                    $leftSeats[] = $seatData;
                } else {
                    $rightSeats[] = $seatData;
                }
            }

            $layout[] = [
                'label' => $rowLabel,
                'left'  => $leftSeats,
                'right' => $rightSeats
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $layout
        ]);
    }
}
