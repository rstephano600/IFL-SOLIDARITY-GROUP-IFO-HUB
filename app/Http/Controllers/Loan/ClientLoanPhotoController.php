<?php

namespace App\Http\Controllers\Loan;

use App\Http\Controllers\Controller;

use App\Models\ClientLoanPhoto;
use App\Models\Client;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClientLoanPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $query = ClientLoanPhoto::with(['client', 'loan', 'creator']);

    // 🔍 Search by description, client name, or loan id
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('description', 'like', "%{$search}%")
              ->orWhereHas('client', function ($q) use ($search) {
                  $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
              })
              ->orWhereHas('loan', function ($q) use ($search) {
                  $q->where('loan_number', 'like', "%{$search}%");
              });
        });
    }

    // 📅 Filter by Date Captured (single or range)
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('date_captured', [$request->start_date, $request->end_date]);
    } elseif ($request->filled('date_captured')) {
        $query->whereDate('date_captured', $request->date_captured);
    }

    // 👤 Filter by Client
    if ($request->filled('client_id')) {
        $query->where('client_id', $request->client_id);
    }

    // 🧑‍💼 Filter by Creator
    if ($request->filled('created_by')) {
        $query->where('created_by', $request->created_by);
    }
    // 📊 Total photos after filters
    $totalPhotos = $query->count();

    // 📤 Export CSV
    if ($request->filled('export') && $request->export === 'csv') {
        $photos = $query->get();

        $filename = 'client_loan_photos_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['Client Name', 'Loan', 'Description', 'Photo', 'Date Captured', 'Created By']);

        foreach ($photos as $photo) {
            fputcsv($handle, [
                $photo->client ? $photo->client->first_name . ' ' . $photo->client->last_name : '-',
                $photo->loan ? $photo->loan->loan_number : '-',
                $photo->description,
                $photo->photo,
                $photo->date_captured,
                optional($photo->creator)->name,
            ]);
        }

        fclose($handle);
        return response()->download($filename)->deleteFileAfterSend(true);
    }

    // 📋 Paginate results
    $photos = $query->latest()->paginate(10);
    $clients = \App\Models\Client::select('id', 'first_name', 'last_name')->get();
    $users = \App\Models\User::select('id', 'name')->get();

    return view('in.loans.client_loan_photos.index', compact('photos', 'clients', 'users', 'totalPhotos'));
}


    /**
     * Show the form for creating a new resource.
     */
public function create(Request $request)
{
    $clients = Client::all();
    $loans = Loan::all();

    $loan = null;
    if ($request->filled('loan_id')) {
        $loan = Loan::with('client')->find($request->loan_id);
    }

    return view('in.loans.client_loan_photos.create', compact('clients', 'loans', 'loan'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'client_loan_id' => 'nullable|exists:loans,id',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'date_captured' => 'nullable|date',
            'description' => 'nullable|string|max:1000',
        ]);

        // Handle file upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('client_loan_photos', 'public');
            $validated['photo'] = $path;
        }

        $validated['created_by'] = Auth::id();

        ClientLoanPhoto::create($validated);

        return redirect()->route('client-loan-photos.index')
                         ->with('success', 'Photo uploaded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ClientLoanPhoto $clientLoanPhoto)
    {
        return view('in.loans.client_loan_photos.show', compact('clientLoanPhoto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClientLoanPhoto $clientLoanPhoto)
    {
        $clients = Client::all();
        $loans = Loan::all();
        return view('in.loans.client_loan_photos.edit', compact('clientLoanPhoto', 'clients', 'loans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClientLoanPhoto $clientLoanPhoto)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'client_loan_id' => 'nullable|exists:loans,id',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'date_captured' => 'nullable|date',
            'description' => 'nullable|string|max:1000',
        ]);

        // Handle new file upload
        if ($request->hasFile('photo')) {
            // delete old photo
            if ($clientLoanPhoto->photo && Storage::disk('public')->exists($clientLoanPhoto->photo)) {
                Storage::disk('public')->delete($clientLoanPhoto->photo);
            }
            $path = $request->file('photo')->store('client_loan_photos', 'public');
            $validated['photo'] = $path;
        }

        $validated['updated_by'] = Auth::id();

        $clientLoanPhoto->update($validated);

        return redirect()->route('client-loan-photos.index')
                         ->with('success', 'Photo updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClientLoanPhoto $clientLoanPhoto)
    {
        if ($clientLoanPhoto->photo && Storage::disk('public')->exists($clientLoanPhoto->photo)) {
            Storage::disk('public')->delete($clientLoanPhoto->photo);
        }

        $clientLoanPhoto->delete();

        return redirect()->route('client-loan-photos.index')
                         ->with('success', 'Photo deleted successfully.');
    }
}
