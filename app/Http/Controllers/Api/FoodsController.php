<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Food;

class FoodsController extends Controller
{
    public function index()
    {
        $foods = Food::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Makanan berhasil diambil',
            'data' => $foods
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        
        if($request)
        {
            $food = Food::create($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Data Makanan berhasil ditambahkan',
                'data' => $food
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Makanan gagal ditambahkan',
        ], 400);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $food = Food::find($id);

        if($food)
        {
            $food->update($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Data Makanan berhasil diupdate',
                'data' => $food
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Makanan tidak ditemukan',
        ], 404);
    }

    public function destroy($id)
    {
        $food = Food::find($id);
        if($food)
        {
            $food->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data Makanan berhasil dihapus',
                'data' => $food
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data Makanan tidak ditemukan',
        ], 404);
    }
}
