<?php

namespace App\Http\Controllers\api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    public function index()
    {
        try {
            $addresses = Address::all();
            return response()->json($addresses);
        } catch (\Exception $e) {
            Log::error('Failed to fetch addresses: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch addresses'], 500);
        }
    }

    public function show(Address $address)
    {
        try {
            return response()->json($address);
        } catch (\Exception $e) {
            Log::error('Failed to fetch address: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch address'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'state' => 'required|string',
                'address_line_1' => 'required|string',
                'address_line_2' => 'required|string',
                'city' => 'required|string',
                'country' => 'required|string',
                'postal_code' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            // Assuming user_id is available from authenticated user
            $user_id = Auth::user()->id;

            $address = Address::create([
                'user_id' => $user_id,
                'state' => $request->state,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'city' => $request->city,
                'country' => $request->country,
                'postal_code' => $request->postal_code,
            ]);

            return response()->json(['message' => 'Address created successfully', 'data' => $address], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create address: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create address'], 500);
        }
    }


    public function update(Request $request, Address $address)
    {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'state' => 'required|string',
                'address_line_1' => 'string',
                'address_line_2' => 'nullable|string',
                'city' => 'string',
                'country' => 'string',
                'postal_code' => 'string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            // Update only the provided fields
            $address->update($request->only([
                'state',
                'address_line_1',
                'address_line_2',
                'city',
                'country',
                'postal_code',
            ]));

            return response()->json(['message' => 'Address updated successfully', 'data' => $address], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update address: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update address'], 500);
        }
    }


    public function destroy(Address $address)
    {
        try {
            $address->delete();
            return response()->json(['message' => 'Address deleted successfully'], 204);
        } catch (\Exception $e) {
            Log::error('Failed to delete address: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete address'], 500);
        }
    }
}
