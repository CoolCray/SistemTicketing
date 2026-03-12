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
        $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];
        $layout = [];

        foreach ($rows as $rowLabel) {
            $rowSeats = $seats->filter(function ($seat) use ($rowLabel) {
                // Since format is 'A-1', explode it
                $parts = explode('-', $seat->seat_number);
                return $parts[0] === $rowLabel;
            })->values();

            // Sort seats by numeric number
            $sortedRowSeats = $rowSeats->sortBy(function ($seat) {
                $parts = explode('-', $seat->seat_number);
                return (int) $parts[1];
            })->values();

            // 24 seats per row (12 left, 12 right)
            $leftSeats = [];
            $rightSeats = [];

            foreach ($sortedRowSeats as $index => $seat) {
                // Determine the status of the seat
                $status = 'available';

                if (in_array($seat->id, $bookedSeatIds)) {
                    $status = 'booked';
                } elseif ($seat->is_blocked) {
                    $status = 'blocked';
                }

                $parts = explode('-', $seat->seat_number);
                $number = (int) $parts[1];

                $seatData = [
                    'db_id'   => $seat->id,
                    'id'      => $seat->seat_number,
                    'label'   => $rowLabel,
                    'number'  => $number,
                    'status'  => $status
                ];

                if ($number <= 12) {
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

    /**
     * Update the blocked status of a given seat.
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'seat_id' => 'required|exists:seats,id'
        ]);

        $seatId = $request->seat_id;
        $seat = DB::table('seats')->where('id', $seatId)->first();

        if (!$seat) {
            return response()->json(['message' => 'Seat not found'], 404);
        }

        // Check if the seat is already booked by user, prevent blocking
        $isBooked = DB::table('transactions')->where('seat_id', $seatId)->exists();
        if ($isBooked) {
            return response()->json(['message' => 'Seat is booked by a transaction, cannot be manually blocked.', 'status' => 'error'], 400);
        }

        $newStatus = !$seat->is_blocked;

        DB::table('seats')
            ->where('id', $seatId)
            ->update(['is_blocked' => $newStatus]);

        return response()->json([
            'status' => 'success',
            'message' => 'Seat status updated',
            'is_blocked' => $newStatus
        ]);
    }
}
