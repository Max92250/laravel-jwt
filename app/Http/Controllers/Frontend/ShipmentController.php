<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipment;
class ShipmentController extends Controller
{

    // Store a newly created shipment in the database
    public function store(Request $request)
    {
        $member = auth()->guard('members')->user();

        // Validation rules
        $validationRules = [
            'name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => ['required', 'string', 'regex:/^\d{6}$/'],
        ];
    
        // Perform validation
        $validatedData = $request->validate($validationRules);
    
        if ($validatedData->fails()) {
            return redirect()->back()->withErrors($validatedData)->withInput();
        }
    
        // Add the member_id to the validated data
        $validatedData['member_id'] = $member->id;
    
        // Create the Shipment model instance
        $shipment = Shipment::create($validatedData);

        return redirect()->back()->with('success', 'Shipment created successfully.')->with('shipment', $shipment);

    
       
    }
/*
    // Show the form for editing the specified shipment
    public function edit(Shipment $shipment)
    {
        return view('shipment.edit', compact('shipment'));
    }

    // Update the specified shipment in the database
    public function update(Request $request, Shipment $shipment)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            // Add validation rules for other shipment fields here
        ]);

        $shipment->update($request->all());

        return redirect()->route('shipment.index')->with('success', 'Shipment updated successfully.');
    }

    // Show the specified shipment
    public function show(Shipment $shipment)
    {
        return view('shipment.show', compact('shipment'));
    }
*/
    // Delete the specified shipment from the database
    public function destroy($id)
    {
        $shipment = Shipment::findOrFail($id);

        // Delete the shipment
        $shipment->delete();

        return redirect()->back()->with('success', 'Shipment deleted successfully.');
    }
    
}
