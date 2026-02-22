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
        $data = Package::create($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditambahkan',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = Package::find($id);
        $data->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diupdate',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $data = Package::find($id);
        $data->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
            'data' => $data
        ]);
    }
}
