<?php

namespace App\Http\Controllers\Donation;

use App\Http\Controllers\Controller;

use App\Models\Donation;
use App\Models\DonationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DonationController extends Controller
{
    /**
     * Display a listing of the donations (with search & pagination)
     */
public function index(Request $request)
{
    $query = Donation::with('createdBy');

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('donation_title', 'like', "%{$search}%")
              ->orWhere('recipient_name', 'like', "%{$search}%")
              ->orWhere('support_type', 'like', "%{$search}%");
        });
    }

    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('donation_date', [$request->start_date, $request->end_date]);
    } elseif ($request->filled('donation_date')) {
        $query->whereDate('donation_date', $request->donation_date);
    }

    if ($request->filled('created_by')) {
        $query->where('created_by', $request->created_by);
    }

    if ($request->filled('recipient_name')) {
        $query->where('recipient_name', 'like', "%{$request->recipient_name}%");
    }

    $totalAmount = $query->sum('amount');

    if ($request->filled('export') && $request->export === 'csv') {
        $donations = $query->get();

        $filename = 'donations_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['Title', 'Recipient', 'Support Type', 'Amount', 'Date', 'Created By']);

        foreach ($donations as $donation) {
            fputcsv($handle, [
                $donation->donation_title,
                $donation->recipient_name,
                $donation->support_type,
                $donation->amount,
                $donation->donation_date,
                optional($donation->createdBy)->name,
            ]);
        }

        fclose($handle);
        return response()->download($filename)->deleteFileAfterSend(true);
    }

    $donations = $query->latest()->paginate(10);
    $users = \App\Models\User::select('id', 'name')->get();

    return view('in.donations.index', compact('donations', 'users', 'totalAmount'));
}



    /**
     * Show the form for creating a new donation
     */
    public function create()
    {
        return view('in.donations.create');
    }

    /**
     * Store a newly created donation with its items
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'donation_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'donation_date' => 'required|date',
            'recipient_name' => 'required|string|max:255',
            'recipient_type' => 'nullable|string|max:255',
            'recipient_contact' => 'nullable|string|max:255',
            'support_type' => 'nullable|string|max:255',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'status' => 'nullable|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_value' => 'nullable|numeric|min:0',
        ]);

            // Handle file upload
    if ($request->hasFile('attachment')) {
        $path = $request->file('attachment')->store('donation_attachments', 'public');
        $validated['attachment'] = $path;
    }


        DB::transaction(function () use ($validated) {
            $donation = Donation::create([
                'donation_title' => $validated['donation_title'],
                'description' => $validated['description'] ?? null,
                'amount' => $validated['amount'],
                'currency' => $validated['currency'],
                'donation_date' => $validated['donation_date'],
                'recipient_name' => $validated['recipient_name'],
                'recipient_type' => $validated['recipient_type'] ?? null,
                'recipient_contact' => $validated['recipient_contact'] ?? null,
                'support_type' => $validated['support_type'] ?? null,
                'attachment' => $validated['attachment'] ?? null,
                'status' => $validated['status'] ?? 'completed',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            foreach ($validated['items'] as $item) {
                $totalValue = ($item['quantity'] ?? 0) * ($item['unit_value'] ?? 0);
                DonationItem::create([
                    'donation_id' => $donation->id,
                    'item_name' => $item['item_name'],
                    'quantity' => $item['quantity'],
                    'unit_value' => $item['unit_value'],
                    'total_value' => $totalValue,
                    'currency' => $donation->currency,
                ]);
            }
        });

        return redirect()->route('donations.index')->with('success', 'Donation created successfully.');
    }

    /**
     * Display the specified donation and its items
     */
    public function show(Donation $donation)
    {
        $donation->load('items', 'createdBy');
        return view('in.donations.show', compact('donation'));
    }

    /**
     * Show the form for editing a donation
     */
    public function edit(Donation $donation)
    {
        $donation->load('items');
        return view('in.donations.edit', compact('donation'));
    }

    /**
     * Update a donation and its items
     */
public function update(Request $request, Donation $donation)
{
    $validated = $request->validate([
        'donation_title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'amount' => 'required|numeric|min:0',
        'currency' => 'required|string|max:10',
        'donation_date' => 'required|date',
        'recipient_name' => 'required|string|max:255',
        'recipient_type' => 'nullable|string|max:255',
        'recipient_contact' => 'nullable|string|max:255',
        'support_type' => 'nullable|string|max:255',
        'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        'status' => 'nullable|string|max:50',
        'items' => 'required|array|min:1',
        'items.*.item_name' => 'required|string|max:255',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.unit_value' => 'nullable|numeric|min:0',
    ]);

    // Handle file upload
    if ($request->hasFile('attachment')) {
        $path = $request->file('attachment')->store('donation_attachments', 'public');
        $validated['attachment'] = $path;
    }

    DB::transaction(function () use ($validated, $donation) {
        $donation->update([
            'donation_title' => $validated['donation_title'],
            'description' => $validated['description'] ?? null,
            'amount' => $validated['amount'],
            'currency' => $validated['currency'],
            'donation_date' => $validated['donation_date'],
            'recipient_name' => $validated['recipient_name'],
            'recipient_type' => $validated['recipient_type'] ?? null,
            'recipient_contact' => $validated['recipient_contact'] ?? null,
            'support_type' => $validated['support_type'] ?? null,
            'attachment' => $validated['attachment'] ?? $donation->attachment, // keep old file if not updated
            'status' => $validated['status'] ?? 'completed',
            'updated_by' => Auth::id(),
        ]);

        // Remove old items and reinsert
        $donation->items()->delete();

        foreach ($validated['items'] as $item) {
            $totalValue = ($item['quantity'] ?? 0) * ($item['unit_value'] ?? 0);
            DonationItem::create([
                'donation_id' => $donation->id,
                'item_name' => $item['item_name'],
                'quantity' => $item['quantity'],
                'unit_value' => $item['unit_value'],
                'total_value' => $totalValue,
                'currency' => $donation->currency,
            ]);
        }
    });

    return redirect()->route('donations.index')->with('success', 'Donation updated successfully.');
}

    /**
     * Remove a donation
     */
    public function destroy(Donation $donation)
    {
        $donation->delete();
        return redirect()->route('donations.index')->with('success', 'Donation deleted successfully.');
    }
}
