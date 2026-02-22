<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Additional;

class AdditionalsController extends Controller
{
    public function index()
    {
        $data = Additional::all();

        return response()->json([
            'status' => 'success',
            'data' => $data,

        ], 200);
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
        $additional->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $additional,

        ], 200);
    }

    public function destroy($id)
    {
        $additional = Additional::find($id);
        $additional->delete();

        return response()->json([
            'status' => 'success',
            'data' => $additional,

        ], 200);
    }
}
