<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\{Sender, Receiver, Box, Shipment, Item, Payment, Dispatch, Billing};

use App\Exports\ExcelExport;


class CustomerController extends Controller
{

    public function CustomerAll()
    {
        $user = auth()->user(); // Get the logged-in user

        if ($user->hasRole('vendor')) {
            // Show only the senders added by this vendor
            $senders = Sender::with(['receiver', 'payments', 'dispatch'])
                ->where('vendor_id', $user->id)
                ->get();
        } else {
            // If user is admin or other role, show all senders
            $senders = Sender::with(['receiver', 'payments', 'dispatch'])->get();
        }

        return view('backend.customer.customer_all', compact('senders'));
    }



    public function CustomerShow($id)
    {
        $sender = Sender::with(['boxes.items'])->findOrFail($id);

        $totalBoxes = $sender->boxes()->count();
        $receivers = Receiver::where('sender_id', $id)->get();
        $shipments = Shipment::where('sender_id', $id)->get();
        $payments = Payment::where('sender_id', $id)->get();
        $dispatchs = Dispatch::where('sender_id', $id)->get();
        $billings = Billing::where('sender_id', $id)->get();
        $totalQuantity = $sender->boxes->sum(function ($box) {
            return $box->items->sum(function ($item) {
                // Extract numeric part from quantity (remove non-numeric characters)
                $numericQuantity = preg_replace('/[^0-9.]/', '', $item->quantity);
                // Return the numeric value or 0 if the quantity is empty or invalid
                return is_numeric($numericQuantity) ? floatval($numericQuantity) : 0;
            });
        });
        $grandTotal = $sender->boxes->sum(function ($box) {
            return $box->items->sum('amount');
        });

        return view('backend.customer.customer_preview', compact('sender', 'receivers', 'billings', 'shipments', 'totalQuantity', 'grandTotal', 'totalBoxes', 'payments', 'dispatchs'));
    }





    public function CustomerAdd()
    {
        $senders = Sender::all();
        $receivers = Receiver::all();

        return view('backend.customer.customer_add', compact('senders', 'receivers',));
    }

    public function CustomerEdit($id)
    {
        $sender = Sender::with(['boxes.items'])->findOrFail($id);

        $receivers = Receiver::where('sender_id', $id)->get();
        $shipments = Shipment::where('sender_id', $id)->get();
        $nextBoxNumber = count($sender->boxes) + 1;
        $allSenders = Sender::all();


        return view('backend.customer.customer_edit', compact('sender', 'shipments', 'receivers', 'nextBoxNumber', 'allSenders'));
    }

    public function CustomerUpdate(Request $request)
    {
        try {


            $sender = Sender::findOrFail($request->id);

            $sender->update([
                'senderName' => $request->senderName,
                'senderPhone' => $request->senderPhone,
                'senderEmail' => $request->senderEmail,
                'senderAddress' => $request->senderAddress,
            ]);

            $this->updateReceiver($request);
            $this->updateShipment($request);
        } catch (\Exception $e) {
            return redirect()->route('customer.all')->with('error', 'An error occurred: ' . $e->getMessage());
        }

        try {
            $this->updateBoxesAndItems($sender, $request);
            return redirect()->route('customer.all')->with('success', 'Data updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('customer.all')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    private function updateReceiver(Request $request)
    {
        try {
            $receiver = Receiver::find($request->id);

            if ($receiver) {
                $receiver->update([
                    'receiverName' => $request->receiverName,
                    'receiverPhone' => $request->receiverPhone,
                    'receiverEmail' => $request->receiverEmail,
                    'receiverPostalcode' => $request->receiverPostalcode,
                    'receiverCountry' => $request->receiverCountry,
                    'receiverAddress' => $request->receiverAddress,
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->route('customer.all')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    private function updateShipment(Request $request)
    {
        try {
            $shipment = Shipment::find($request->id);
            if ($shipment) {
                $shipment->update([
                    'shipment_via' => $request->shipment_via,
                    'actual_weight' => $shipment->actual_weight,
                    'invoice_date' => $request->invoice_date,
                    'dimension' => $request->dimension,
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->route('customer.all')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    private function updateBoxesAndItems($sender, Request $request)
    {
        try {
            // Initialize an array to store the data of the deleted boxes
            $deletedBoxes = [];

            $sender->boxes()->each(function ($box) use (&$deletedBoxes) {
                // Store the box data (weight and dimension) before deletion
                $deletedBoxes[$box->id] = [
                    'box_weight' => $box->box_weight,   // Store the weight of the box
                    'dimension' => $box->box_dimension, // Store the dimension of the box (example format: 12*12*12)
                ];

                // Delete the items related to the box
                $box->items()->delete();

                // Delete the box itself
                $box->delete();
            });
            // dd($deletedBoxes);
            $nextBoxNumber = 1;  // Initialize box number counter

            // Loop through the new box data from the request
            foreach ($request->boxes as $box_id => $box_data) {
                // If there is a previously deleted box data available for this box_id, use it
                if (isset($deletedBoxes[$box_id])) {
                    $deletedBoxData = $deletedBoxes[$box_id];
                    // Assuming box_id starts from 1 and array is 0-indexed
                    //   dd($deletedBoxData);

                    // Create a new box with the required details
                    try {
                        $box = Box::create([
                            'sender_id' => $sender->id,
                            'box_number' => 'Box' . $nextBoxNumber,  // Set the box number (Box1, Box2, etc.)
                            'box_weight' => $deletedBoxData['box_weight'],  // Use the stored weight from deleted box
                            'box_dimension' => $deletedBoxData['dimension'],  // Use the stored dimension from deleted box
                        ]);
                    } catch (\Exception $e) {
                        return redirect()->route('customer.all')->with('error', 'An error occurred: ' . $e->getMessage());
                    }
                } else {
                    // If this is a new box (no previous data to retrieve)
                    $box = Box::create([
                        'sender_id' => $sender->id,
                        'box_number' => 'Box' . $nextBoxNumber,  // Set the box number (Box1, Box2, etc.)

                    ]);
                }
                // Increment the box number for the next iteration
                $nextBoxNumber++;

                foreach ($box_data['items'] as $item_data) {
                    Item::create([
                        'box_id' => $box->id,
                        'item' => $item_data['item'],
                        'hs_code' => $item_data['hs_code'],
                        'quantity' => $item_data['quantity'],
                        'unit_rate' => $item_data['unit_rate'],
                        'amount' => $item_data['amount'],
                    ]);
                }
            }
        } catch (\Exception $e) {
            return redirect()->route('customer.all')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }





    public function CustomerDelete($id)
    {
        Sender::findOrFail($id)->delete();

        $notification = [
            'message' => 'Customer Deleted Successfully',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }



    public function calculateTotalWeight($boxes, $weight)
    {
        // Sum weights passed as an array of strings
        $totalWeight = 0;

        foreach ($weight as $w) {
            $totalWeight += (float) $w;  // convert to float just in case
        }



        return $totalWeight;
    }
    public function CustomerStore(Request $request)
    {
        // Validate required structure
        $weight = $request->input('box_weight', []);
        $validated = $request->validate([
            'boxes' => 'required|array',
            'senderEmail' => 'nullable|email',
            'receiverEmail' => 'nullable|email',
        ]);

        $lastInvoice = Sender::max('invoiceId');
        $nextInvoiceId = $lastInvoice ? $lastInvoice + 1 : 100;

        try {
            // Create Sender
            $sender = Sender::create([
                'senderName'     => $request->senderName,
                'senderPhone'    => $request->senderPhone ?? null,
                'senderEmail'    => $request->senderEmail ?? null,
                'senderAddress'  => $request->address1 ?? null, // Optional
                'company_name'   => $request->company_name ?? null,
                'address1'       => $request->address1 ?? null,
                'address2'       => $request->address2 ?? null,
                'address3'       => $request->address3 ?? null,
                'status'         => 'pending',
                'invoiceId'      => $nextInvoiceId,
                'vendor_id'      => auth()->id(), //
            ]);



            // Create Receiver
            Receiver::create([
                'sender_id'            => $sender->id,
                'receiverName'         => $request->receiverName,
                'receiverPhone'        => $request->receiverPhone ?? null,
                'receiverEmail'        => $request->receiverEmail ?? null,
                'receiverAddress'      => $request->receiverAddress ?? null,
                'receiverPostalcode'   => $request->receiverPostalcode ?? null,
                'receiverCountry'      => $request->receiverCountry ?? null,
                'receiver_company_name'  => $request->receiver_company_name ?? null,
            ]);

            // Create Shipment
            Shipment::create([
                'sender_id'     => $sender->id,
                'shipment_via'  => $request->shipment_via ?? null,
                'invoice_date'  => $request->invoice_date,
                'dimension'     => $request->dimension ?? null,
            ]);

            // Create Boxes and Items
            $this->createBoxesAndItems($sender, $validated['boxes'], $weight);

            return redirect()->route('customer.all')->with('success', 'Data saved successfully.');
        } catch (\Exception $e) {
            return redirect()->route('customer.all')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }



    private function createBoxesAndItems($sender, $boxes, $weight)
    {
        foreach ($boxes as $index => $boxData) {
            try {
                $box = Box::create([
                    'sender_id'   => $sender->id,
                    'box_number'  => 'Box' . ($index + 1),
                    'box_weight'      => $weight[$index] ?? null, // Save weight if needed
                ]);
            } catch (\Exception $e) {
                return redirect()->route('customer.all')->with('error', $e->getMessage());
            }

            foreach ($boxData['items'] as $itemData) {
                Item::create([
                    'box_id'     => $box->id,
                    'item'       => $itemData['item'],
                    'hs_code'    => $itemData['hs_code'] ?? null,
                    'quantity'   => $itemData['quantity'],
                    'unit_rate'  => $itemData['unit_rate'] ?? null,
                    'amount'     => $itemData['amount'],
                ]);
            }
        }

        $shipment = Shipment::where('sender_id', $sender->id)->first();
        if ($shipment) {
            $shipment->update([



                'actual_weight' => $this->calculateTotalWeight($boxes, $weight),

            ]);
        }

        return redirect()->route('customer.all')->with('success', 'Data saved successfully.');
    }







    public function exportToExcel($id)
    {



        $sender = Sender::with(['boxes.items'])->findOrFail($id);
        $totalBoxes = $sender->boxes()->count();

        $receivers = Receiver::where('sender_id', $id)->get();
        $shipments = Shipment::where('sender_id', $id)->get();

        $receiverName = $receivers->first()->receiverName ?? 'default_sender';   // Fallback to 'default_sender' if no name is available
        $filename = $receiverName . '.xlsx';

        return Excel::download(new ExcelExport($sender, $shipments, $receivers, $totalBoxes), $filename);
    }





    public function printInvoice($id)
    {
        $sender = Sender::with(['boxes.items'])->findOrFail($id);

        $totalBoxes = $sender->boxes()->count();


        $receivers = Receiver::where('sender_id', $id)->get();
        $shipments = Shipment::where('sender_id', $id)->get();











        return view('backend.customer.print', compact('sender', 'shipments', 'receivers', 'totalBoxes'));
    }

    public function addweight($id)
    {
        $sender = Sender::with(['boxes.items'])->findOrFail($id);
        $receivers = Receiver::where('sender_id', $id)->get();
        $shipments = Shipment::where('sender_id', $id)->get();
        return view('backend.customer.customer_addweight', compact('sender', 'shipments', 'receivers'));
    }

    public function CustomerUpdateWeight(Request $request)
    {
        $totalWeight = 0;

        $weightDetails = ''; // String to store each box's weight in the desired format
        $dime = '';

        foreach ($request->boxes as $boxId => $boxData) {
            // Find the box by its ID
            $box = Box::findOrFail($boxId);
            // Update the box weight
            $box->box_weight = $boxData['weight'];
            $box->box_dimension = $boxData['dimension'];
            $totalWeight += $boxData['weight'];

            // Add this box's weight to the string
            $weightDetails .= "{$box['box_number']}:{$boxData['weight']}Kg, ";
            $dime .= "{$box['box_number']}: {$boxData['dimension']}, ";

            // Save the updated box data to the database
            $box->save();
        }
        // Append the total weight to the string

        $weightDetails .= "Total Weight:{$totalWeight}Kg";

        // Update the actual_weight field in the shipments table



        $sender_id = $request->id;
        $shipments = Shipment::where('sender_id', $sender_id)->get();
        foreach ($shipments as $shipment) {
            $shipment->update([
                'actual_weight' => $weightDetails,  // Store the formatted string
                'dimension' => $dime,
            ]);
        }


        // Redirect with success message
        return redirect()->route('customer.all')->with('success', 'Box weights updated successfully.');
    }

    public function recycle()
    {
        $senders = Sender::onlyTrashed()->get();
        return view('backend.customer.recyclebin', compact('senders'));
    }
    //restore
    public function restore($id)
    {
        $senders = Sender::withTrashed()->find($id);
        $senders->restore();
        return redirect()->route('customer.all')->with('success', 'User deleted successfully!');
    }

    //bulk  restore
    public function bulkRestore(Request $request)
    {
        $ids = $request->sender_ids;
        Sender::onlyTrashed()->whereIn('id', $ids)->restore();

        return response()->json(['message' => 'Selected senders restored successfully.']);
    }
    //Bulk delete
    public function bulkDelete(Request $request)
    {
        $ids = $request->sender_ids;
        Sender::whereIn('id', $ids)->delete();

        return response()->json(['message' => 'Selected senders deleted successfully.']);
    }

    //bulk force delete(delete from database)
    public function bulkForceDelete(Request $request)
    {
        $ids = $request->sender_ids;

        Sender::whereIn('id', $ids)->forceDelete();


        return response()->json(['message' => 'Selected senders permanently deleted.']);
    }



    //     public function checkSender(Request $request)
    // {
    //     $name = $request->query('name');
    //     $sender = Sender::where('name', $name)->first();

    //     if ($sender) {
    //         return response()->json([
    //             'exists' => true,
    //             'phone' => $sender->senderPhone,
    //             'email' => $sender->senderEmail,
    //             // Add other fields here
    //         ]);
    //     } else {
    //         return response()->json(['exists' => false]);
    //     }
    // }

}
