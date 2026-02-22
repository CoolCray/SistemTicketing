<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index()
    {
        $data = Attendance::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diambil',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $data = Attendance::create($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = Attendance::find($id);
        $data->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diupdate',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $data = Attendance::find($id);
        $data->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
            'data' => $data
        ]);
    }
}
