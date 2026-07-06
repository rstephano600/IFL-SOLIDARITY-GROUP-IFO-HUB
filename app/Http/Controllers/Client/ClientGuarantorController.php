<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientGuarantor;
use App\Models\Client;

class ClientGuarantorController extends Controller
{
    /**
     * Display a listing of the guarantors.
     */
    public function index(Request $request)
    {
        $query = ClientGuarantor::query()->with('client');

        // Search by name, national ID, or phone
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter by client
        if ($clientId = $request->input('client_id')) {
            $query->where('client_id', $clientId);
        }

        $guarantors = $query->orderBy('created_at', 'desc')->paginate(10);
        $clients = Client::orderBy('first_name')->get();

        return view('in.clients.guarantors.index', compact('guarantors', 'clients'));
    }

    /**
     * Show the form for creating a new guarantor.
     */
    public function create(Request $request)
    {
        $client_id = $request->query('client_id');
        return view('in.clients.guarantors.create', compact('client_id'));
    }

    /**
     * Store a newly created guarantor in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'national_id' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'required|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0',
            'relationship_to_client' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,declined',
        ]);

        ClientGuarantor::create($validated);

        return redirect()
            ->route('clients.show', $validated['client_id'])
            ->with('success', 'Guarantor added successfully.');
    }

    /**
     * Display the specified guarantor.
     */
    public function show($id)
    {
        $guarantor = ClientGuarantor::with('client')->findOrFail($id);
        return view('in.clients.guarantors.show', compact('guarantor'));
    }

    /**
     * Show the form for editing the specified guarantor.
     */
    public function edit($id)
    {
        $guarantor = ClientGuarantor::findOrFail($id);
        return view('in.clients.guarantors.edit', compact('guarantor'));
    }

    /**
     * Update the specified guarantor in storage.
     */
    public function update(Request $request, $id)
    {
        $guarantor = ClientGuarantor::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'national_id' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'required|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0',
            'relationship_to_client' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,declined',
        ]);

        $guarantor->update($validated);

        return redirect()
            ->route('clients.show', $guarantor->client_id)
            ->with('success', 'Guarantor updated successfully.');
    }

    /**
     * Remove the specified guarantor from storage.
     */
    public function destroy($id)
    {
        $guarantor = ClientGuarantor::findOrFail($id);
        $clientId = $guarantor->client_id;
        $guarantor->delete();

        return redirect()
            ->route('clients.show', $clientId)
            ->with('success', 'Guarantor deleted successfully.');
    }
}
