<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Additional;

class AdditionalsController extends Controller
{
    public function index()
    {
        return Additional::paginate(10);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required',
                'price' => 'required',
            ]
        );

        $additional = Additional::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data additional berhasil ditambahkan',
            'data' => $additional,

        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate(
            [
                'name' => 'required',
                'price' => 'required',
            ]
        );

        $additional = Additional::find($id);

        if ($additional) {
            $additional->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Data additional berhasil diupdate',
                'data' => $additional,

            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data additional tidak ditemukan',

        ], 404);
    }

    public function destroy($id)
    {
        $additional = Additional::find($id);

        if ($additional) {
            $additional->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data additional berhasil dihapus',
                'data' => $additional,

            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Data additional tidak ditemukan',

        ], 404);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        return Additional::where('name', 'like', '%' . $query . '%')->paginate(10);
    }
}
