<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Size;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function show_SubSizes($id)
    {
        $size = Size::findOrFail($id);

        // Get the authenticated user
        $user = auth()->user();

        // Fetch sizes based on the customer ID associated with the authenticated user
        $sizes = Size::where('customer_id', $user->customer_id)
            ->with('createdBy', 'updatedBy')
            ->get();

        // Fetch the usernames for the creators of the sizes

        // Return the view with the parent size, usernames, and filtered sizes
        return view('product.subsizes', compact('size', 'sizes'));

    }
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'sizeName' => 'required|string|max:255', // Assuming 'sizeName' is the name of the input field
        ]);

        $user = Auth::user();

        try {
            // Find the size by ID
            $size = Size::findOrFail($id);

            // Update the size name
            $size->update([
                'name' => $request->input('sizeName'),
                'updated_by' => $request->user()->id,

            ]);

            return redirect()->back()->with('success', 'Size name updated successfully.');
        } catch (\Exception $e) {
            // Handle any exceptions
            return redirect()->back()->with('error', 'Failed to update size name.');
        }
    }

    public function show_Sizes(Request $request)
    {
        if (auth()->user()->haspermission('access-size-page')) {
            $user = Auth::user();

            $sizes = Size::where('customer_id', $user->customer_id)->get();
            // Fetch the username for the creator of the sizes
            $createdByUsername = User::whereIn('id', $sizes->pluck('created_by')->unique())
                ->pluck('username', 'id');

            // Fetch the usernames for the updaters of the sizes
            $updatedByUsername = User::whereIn('id', $sizes->pluck('updated_by')->unique())
                ->pluck('username', 'id');

            return view('product.size', [
                'sizes' => $sizes,
                'createdByUsername' => $createdByUsername,
                'updatedByUsername' => $updatedByUsername,
            ]);
        } else {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Validate the incoming request data

        $request->validate([
            'name' => 'required|string',
            'parent_id' => 'nullable|exists:sizes,id',
            'status' => 'nullable|in:active,inactive',

        ]);

        // Get the authenticated user
        $user = Auth::user();

        // Create a new Size object with the authenticated user's ID as created_by and updated_by
        $size = new Size([
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id'),
            'status' => $request->input('status'),
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'customer_id' => $user->customer_id,
        ]);

        // Save the size to the database
        $size->save();

        return redirect()->back()->with('success', 'Size name successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
