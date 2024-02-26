<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'identifier' => 'required|string|unique:customers',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {

            DB::beginTransaction();

            // Create the customer
            $customer = Customer::create([
                'name' => $request->name,
                'identifier' => $request->identifier,
                'created_by' => $request->user()->id,
                'updated_by' => $request->user()->id,
                // Add more fields for customer if needed
            ]);
            DB::commit();

            return redirect()->back()->with('success', 'Customer created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            // Rollback the transaction if an exception occurred
            return back()->withInput()->withErrors(['error' => 'Failed to create customer. Please try again.']);
        }
    }

    public function show(Request $request)
    {

        $userRole = Auth::user()->type;

        // Fetch data based on the user's role
        if ($userRole === 'admin') {
            $customers = Customer::with('createdBy', 'updatedBy')->get();
            return view('admin.dashboard', ['customers' => $customers]);
        } elseif ($userRole === 'user') {

            if (auth()->user()->hasPermission('access-dashboard')) {
                $customer = Customer::where('id', $request->user()->customer_id)->first();

                if ($customer) {
                    // Fetch products for the user's customer
                    $products = Product::where('customer_id', $customer->id)->with('createdBy', 'updatedBy')->latest()->get();

                    return view('admin.dashboard', ['products' => $products]);
                }
            } else {
                abort(403);
            }

        }

    }

    public function search(Request $request)
    {

        // Get the search query from the request
        $searchQuery = $request->input('q');
        try {
            // Query to fetch customers
            $customersQuery = Customer::query();
            // If a search query is provided, filter customers based on the search query
            if ($searchQuery) {
                $customersQuery->where('name', 'like', '%' . $searchQuery . '%');
                // You can add more conditions as needed for filtering
            }
            // Fetch the customers
            $customers = $customersQuery->get();
            // Return the view with the search results
            return view('admin.dashboard', ['customers' => $customers]);
        } catch (\Exception $exception) {
            // Handle any exceptions, if needed
            return response()->json(['status' => 'error', 'message' => 'Failed to search customers']);
        }
    }
    public function update(Request $request, $id)
    {
        // Validate the incoming data from the client-side
        $customer = Customer::find($id);

        $validator = $request->validate([
            'name' => 'sometimes|required|string',
            'identifier' => 'sometimes|required|string|unique:customers,identifier,' . $id,
        ]);

        // Update the customer details if changes are made
        if ($request->filled('name')) {
            $customer->name = $request->input('name');
        }
        if ($request->filled('identifier')) {
            $customer->identifier = $request->input('identifier');
        }
        // Update other fields as needed
        $customer->updated_by = $request->user()->id;

        // Save the changes
        $customer->save();

        return redirect()->back()->with('success', 'Customer updated successfully.');
    }

}
