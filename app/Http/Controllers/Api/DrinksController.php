<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Drink;

class DrinksController extends Controller
{
    public function index()
    {
        $drinks = Drink::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Minuman berhasil diambil',
            'data' => $drinks
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        if($request)
        {
            $drink = Drink::create($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Data Minuman berhasil ditambahkan',
                'data' => $drink
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Minuman gagal ditambahkan',
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $drink = Drink::find($id);
        if($drink)
        {
            $drink->update($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Data Minuman berhasil diupdate',
                'data' => $drink
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Minuman tidak ditemukan',
        ], 404);
    }

    public function destroy($id)
    {
        $drink = Drink::find($id);
        if($drink)
        {
            $drink->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Minuman berhasil dihapus',
                'data' => $drink
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Minuman tidak ditemukan',
        ], 404);
    }
}
