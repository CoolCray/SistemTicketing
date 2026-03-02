<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        return Customer::paginate(10);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'nis' => 'required',
            'name_student' => 'required'
        ]);

        $data = Customer::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $data,

        ], 200);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'nis' => 'required',
            'name_student' => 'required'
        ]);

        $customer = Customer::find($id);

        if ($customer) {
            $customer->update($validated);
            return response()->json([
                'status' => 'success',
                'data' => $customer,

            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Customer not found',

        ], 404);
    }


    public function destroy($id)
    {
        $customer = Customer::find($id);

        if ($customer) {
            $customer->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Customer deleted succces'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Customer not Found'
        ], 404);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        return Customer::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone_number', 'like', "%{$query}%")
            ->orWhere('nis', 'like', "%{$query}%")
            ->orWhere('name_student', 'like', "%{$query}%")
            ->paginate(10);
    }
}
