<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\{Sender, Receiver, Box, Shipment, Item,};

use App\Exports\ExcelExport;


class CustomerController extends Controller
{

    public function CustomerAll()
    {
        $senders = Sender::select('id', 'invoiceId', 'trackingId', 'senderName', 'senderPhone', 'senderEmail', 'senderAddress')->get();
        return view('backend.customer.customer_all', compact('senders'));
    }

    public function CustomerShow($id)
    {
        $sender = Sender::with(['boxes.items'])->findOrFail($id);

        $totalBoxes = $sender->boxes()->count();


        $receivers = Receiver::where('sender_id', $id)->get();
        $shipments = Shipment::where('sender_id', $id)->get();

        $totalQuantity = $sender->boxes->sum(function ($box) {
            return $box->items->sum('quantity');
        });

        $grandTotal = $sender->boxes->sum(function ($box) {
            return $box->items->sum('amount');
        });

        return view('backend.customer.customer_preview', compact('sender', 'receivers', 'shipments', 'totalQuantity', 'grandTotal', 'totalBoxes'));
    }

    public function CustomerAdd()
    {
        return view('backend.customer.customer_add');
    }

    public function CustomerEdit($id)
    {
        $sender = Sender::with(['boxes.items'])->findOrFail($id);
        $receivers = Receiver::where('sender_id', $id)->get();
        $shipments = Shipment::where('sender_id', $id)->get();
        $nextBoxNumber = count($sender->boxes) + 1;

        return view('backend.customer.customer_edit', compact('sender', 'shipments', 'receivers', 'nextBoxNumber'));
    }

    public function CustomerUpdate(Request $request)
    {
        $sender = Sender::findOrFail($request->id);

        $sender->update([
            'senderName' => $request->senderName,
            'senderPhone' => $request->senderPhone,
            'senderEmail' => $request->senderEmail,
            'senderAddress' => $request->senderAddress,
        ]);

        $this->updateReceiver($request);
        $this->updateShipment($request);

        try {
            $this->updateBoxesAndItems($sender, $request);
            return redirect()->route('customer.all')->with('success', 'Data updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the data.');
        }
    }

    private function updateReceiver(Request $request)
    {
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
    }

    private function updateShipment(Request $request)
    {
        $shipment = Shipment::find($request->id);
        if ($shipment) {
            $shipment->update([
                'shipment_via' => $request->shipment_via,
                'actual_weight' => $request->actual_weight,
                'invoice_date' => $request->invoice_date,
                'dimension' => $request->dimension,
            ]);
        }
    }

    private function updateBoxesAndItems($sender, Request $request)
    {
        $sender->boxes()->each(function ($box) {
            $box->items()->delete();
            $box->delete();
        });
        $nextBoxNumber = 1;
        foreach ($request->boxes as $box_id => $box_data) {
            $box = Box::create([
                'sender_id' => $sender->id,
                'box_number' => 'Box' . $nextBoxNumber,
                $nextBoxNumber++
            ]);

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


    public function CustomerStore(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
            'boxes' => 'required|array',
        ]);
        // dd($request);
        try {
            $sender = Sender::create([
                'senderName' => $request->senderName,
                'senderPhone' => $request->senderPhone,
                'senderEmail' => $request->senderEmail,
                'senderAddress' => $request->senderAddress,
            ]);
            $sender->save();
            $lastInvoice = Sender::max('invoiceId');
            $sender->invoiceId = $lastInvoice ? $lastInvoice + 1 : 100;
            $sender->save();

            Receiver::create([
                'sender_id' => $sender->id,
                'receiverName' => $request->receiverName,
                'receiverPhone' => $request->receiverPhone,
                'receiverEmail' => $request->receiverEmail,
                'receiverAddress' => $request->receiverAddress,
                'receiverPostalcode' => $request->receiverPostalcode,
                'receiverCountry' => $request->receiverCountry,
            ]);

            Shipment::create([
                'sender_id' => $sender->id,
                'shipment_via' => $request->shipment_via,
                // 'actual_weight' => $request->actual_weight,
                'invoice_date' => $request->invoice_date,
                'dimension' => $request->dimension,
            ]);

            $this->createBoxesAndItems($sender, $validated['boxes']);

            return redirect()->route('customer.all')->with('success', 'Data saved successfully.');
        } catch (\Exception $e) {
            return redirect()->route('customer.all')->with('error', 'An error occurred while saving the data.');
        }
    }

    private function createBoxesAndItems($sender, $boxes)
    {
        foreach ($boxes as $index => $boxData) {

            // dd($boxData);
            try {
                $box = Box::create([
                    'sender_id' => $sender->id,
                    'box_number' => 'Box' . ($index + 1),
                    // 'box_weight' => $boxData['totalWeight'],
                ]);
            } catch (\Exception $e) {
                // Return the exact error message from the exception
                return redirect()->route('customer.all')->with('error', $e->getMessage());
            }


            foreach ($boxData['items'] as $itemData) {
                Item::create([
                    'box_id' => $box->id,

                    'item' => $itemData['item'],
                    'hs_code' => $itemData['hs_code'],
                    'quantity' => $itemData['quantity'],
                    'unit_rate' => $itemData['unit_rate'],
                    'amount' => $itemData['amount'],
                ]);
            }
        }
    }

    public function exportToExcel($id)
    {
        $sender = Sender::with(['boxes.items'])->findOrFail($id);
        $totalBoxes = $sender->boxes()->count();

        $receivers = Receiver::where('sender_id', $id)->get();
        $shipments = Shipment::where('sender_id', $id)->get();

        return Excel::download(new ExcelExport($sender, $shipments, $receivers, $totalBoxes), 'invoice.xlsx');
    }

    public function printInvoice($id)
    {
        $sender = Sender::with(['boxes.items'])->findOrFail($id);
        $receivers = Receiver::where('sender_id', $id)->get();
        $shipments = Shipment::where('sender_id', $id)->get();

        return view('backend.customer.print', compact('sender', 'shipments', 'receivers'));
    }

    public function addweight($id)
    {
        $sender = Sender::with(['boxes.items'])->findOrFail($id);
        $receivers = Receiver::where('sender_id', $id)->get();
        $shipments = Shipment::where('sender_id', $id)->get();
        return view('backend.customer.customer_addweight', compact('sender', 'shipments', 'receivers'));
    }

    public function CustomerUpdateWeight(Request $request  )
    {
        $totalWeight = 0;
        foreach ($request->boxes as $boxId => $boxData) {
            // Find the box by its ID
            $box = Box::findOrFail($boxId);
            // Update the box weight
            $box->box_weight = $boxData['weight'];
            $totalWeight += $boxData['weight'];
            // Save the updated box data to the database
            $box->save();
        }
        $sender_id = $request->id;
        $shipments = Shipment::where('sender_id', $sender_id)->get();

        foreach ($shipments as $shipment) {
          
            $shipment->update([
                'actual_weight' => $totalWeight,
            ]);
        }
        // Redirect with success message
        return redirect()->route('customer.all')->with('success', 'Box weights updated successfully.');
    }
}
