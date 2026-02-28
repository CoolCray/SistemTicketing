<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackagesController extends Controller
{
    public function index()
    {
        $data = Package::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diambil',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required',
                'price' => 'required',
                'total_seats'=> 'required'

            ]
        );

        $data = Package::create($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan',
            'data' => $data
        ], 200);
    }

    public function update(Request $request, $id)
    {

        $data = Package::find($id);

        if($data)
            {
            $data->update($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil diupdate',
                'data' => $data
            ]);
            }
        return response()->json([
            'status'=> 'error',
            'message'=> 'Package tidak ditemukan'
        ],404);

    }

    public function destroy($id)
    {
        $data = Package::find($id);

        if ($data) {
            $data->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus',
                'data' => $data
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Package tidak ditemukan'
        ], 404);
    }
}
