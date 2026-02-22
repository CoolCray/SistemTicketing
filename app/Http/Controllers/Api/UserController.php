<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $user = User::all();

        return response()->json([
            'status' => 'success',
            'data' => $user,

        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'email' => 'required',
            'password'=> 'required|min:8',

        ]);

        $user = User::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $user,

        ], 200);
    }

    public function update(Request $request, $id)
    {

        $user = User::find($id);

        if($user){
            $user->update($request->all());

            return response()->json([
                'status' => 'success',
                'data' => $user,

            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User not found',

        ], 404);
 
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if($user){
            $user->delete();
            return response()->json([
                'status' => 'success',
                'data' => $user,

            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'message' => 'User not found',

        ], 404);
    }
}
