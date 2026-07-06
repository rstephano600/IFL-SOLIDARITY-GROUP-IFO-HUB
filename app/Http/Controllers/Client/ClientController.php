<?php
namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;

use App\Models\Client;
use App\Models\User;
use App\Models\Employee;
use App\Models\GroupCenter;
use App\Models\Group;
use App\Helpers\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Display a listing of clients with search, filter, and pagination.
     */
    public function index(Request $request)
    {
            $user = auth()->user();
        // $query = Client::query();
        $query = Client::where('status', 'active')
        ->when($user->hasRole('loanofficer'), function ($query) use ($user) {
                // Assuming `Employee` is linked to `User` via user_id
                $employee = Employee::where('user_id', $user->id)->first();
                if ($employee) {
                    $query->where('credit_officer_id', $employee->id);
                }
            })
        ->with(['group', 'groupCenter']);
        // 🔍 Search by name, email, phone, or business name
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('business_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // 🎯 Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // 🏦 Filter by assigned loan officer
        if ($officer = $request->input('loan_officer')) {
            $query->where('credit_officer_id', $officer);
        }

        // ✅ Filter by KYC completion
        if ($kyc = $request->input('kyc_completed')) {
            $query->where('kyc_completed', $kyc);
        }

        // Pagination
        $clients = $query->orderBy('created_at', 'desc')->paginate(30)->appends($request->query());

        $loanOfficers = User::where('role', 'loanofficer')->get();

        return view('in.clients.index', compact('clients', 'loanOfficers'));
    }

    /**
     * Show the form for creating a new client.
     */

public function create(Request $request)
{
    // Fetch lists
    $loanOfficers = User::where('role', 'loanOfficer')->get();
    $centres = Group::where('is_active', true)->get();
    $creditOfficers = Employee::where('is_active', true)->get();

    // Capture from query params (like ?group_id=3)
    $selectedGroupId = $request->get('group_id');
    $selectedLoanOfficerId = $request->get('loan_officer_id');
    $selectedCreditOfficerId = $request->get('credit_officer_id');

    return view('in.clients.create', compact(
        'loanOfficers',
        'centres',
        'creditOfficers',
        'selectedGroupId',
        'selectedLoanOfficerId',
        'selectedCreditOfficerId'
    ));
}

    /**
     * Store a newly created client in storage.
     */
 public function store(Request $request)
{
    $validated = $request->validate([
        'group_center_id' =>'nullable|exists:group_centers,id',
        'group_id' => 'nullable|exists:groups,id',
        'client_type' => 'required|string|max:255',
        'business_name' => 'nullable|string|max:255',
        'business_capital'=> 'nullable|numeric|min:0',
        'business_income'=> 'nullable|numeric|min:0',
        'business_location'=> 'nullable|string|max:255',
        'partner_in_business'=> 'nullable|string|max:255',
        'business_registration_number' => 'nullable|string|max:255',
        'tax_identification_number' => 'nullable|string|max:255',
        'industry_sector' => 'nullable|string|max:255',
        'years_in_business' => 'nullable|integer|min:0',
        'months_in_business' => 'nullable|integer|min:0',
        'number_of_employees' => 'nullable|integer|min:0',
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:clients,email',
        'phone' => 'required|string|max:20|unique:clients,phone',
        'alternative_phone' => 'nullable|string|max:20',
        'address_line1' => 'nullable|string|max:255',
        'address_line2' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'state_province' => 'nullable|string|max:255',
        'postal_code' => 'nullable|string|max:20',
        'country' => 'nullable|string|max:100',
        'national_id' => 'nullable|string|max:255',
        'gender' => 'nullable|in:male,female,other',
        'marital_status' => 'nullable|in:single,married,divorced,widowed',
        'spouse_name' => 'nullable|string|max:255',
        'other_name' => 'nullable|string|max:255',
        'date_of_birth' => 'nullable|date',
        'street_leader' => 'nullable|string|maz:255',
        'profile_picture' => 'nullable|string', 
        'sign_image' => 'nullable|string', 
        'credit_score' => 'nullable|numeric|min:0|max:1000',
        'credit_rating' => 'nullable|string|max:50',
        'risk_category' => 'nullable|string|max:50',
        'status' => 'required|string|max:50',
        'blacklist_reason' => 'nullable|string|max:255',
        'credit_officer_id' => 'nullable|exists:users,id',
        'kyc_completed' => 'boolean',
    ]);

    $validated['kyc_completed_at'] = $request->kyc_completed ? now() : null;

    $creditOfficerId = Group::findOrFail($validated['group_id']);

    $validated['credit_officer_id'] = $creditOfficerId->credit_officer_id;
    $validated['group_center_id'] = $creditOfficerId->group_center_id;

    Client::create($validated);

    return back()->with('success', 'User' . $validated['first_name'] . ' ' . $validated['last_name'] . ' created successfully!');
    }


    /**
     * Display the specified client.
     */
     public function show($id)
     {
         $client = Client::with(['loanOfficer', 'documents', 'financialInfo', 'guarantor'])->findOrFail($id);

         return view('in.clients.show', compact('client'));
     }

    /**
     * Show the form for editing the specified client.
     */
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        $loanOfficers = User::where('role', 'loanofficer')->get();
        $centres = Group::where('is_active', true)->get();
        return view('in.clients.edit', compact('client', 'loanOfficers','centres'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'group_center_id' =>'nullable|exists:group_centers,id',
            'group_id' => 'nullable|exists:groups,id',
            'client_type' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'business_capital'=> 'nullable|numeric|min:0',
            'business_income'=> 'nullable|numeric|min:0',
            'business_location'=> 'nullable|string|max:255',
            'partner_in_business'=> 'nullable|string|max:255',
            'business_registration_number' => 'nullable|string|max:255',
            'tax_identification_number' => 'nullable|string|max:255',
            'industry_sector' => 'nullable|string|max:255',
            'years_in_business' => 'nullable|integer|min:0',
            'months_in_business' => 'nullable|integer|min:0',
            'number_of_employees' => 'nullable|integer|min:0',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id . ',id',
            'phone' => 'required|string|max:20|unique:clients,phone,' . $client->id . ',id',
            'alternative_phone' => 'nullable|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state_province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',

            'national_id' => 'nullable|string|max:255',
            'gender' => 'nullable|in:male,female,other',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'spouse_name' => 'nullable|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'is_street_leader' => 'nullable|boolean',
            'profile_picture' => 'nullable|string', 
            'sign_image' => 'nullable|string', 
            'credit_score' => 'nullable|numeric|min:0|max:1000',
            'credit_rating' => 'nullable|string|max:50',
            'risk_category' => 'nullable|string|max:50',
            'status' => 'required|string|max:50',
            'blacklist_reason' => 'nullable|string|max:255',
            'credit_officer_id' => 'nullable|exists:users,id',
            'kyc_completed' => 'boolean',
        ]);

        $validated['kyc_completed_at'] = $request->kyc_completed ? now() : null;

        $creditOfficerId = Group::findOrFail($validated['group_id']);
        $validated['credit_officer_id'] = $creditOfficerId->credit_officer_id;
        $validated['group_center_id'] = $creditOfficerId->group_center_id;

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
        
    }


    /**
     * Remove the specified client from storage.
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }

        public function export(Client $client)
    {
        // 1. Define the CSV header row based on all fields
        $headers = [
            'Client ID', 'Client Type', 'First Name', 'Middle Name', 'Last Name', 'Other Name', 'Gender',
            'Date of Birth', 'Marital Status', 'Spouse Name', 'National ID',
            'Email', 'Phone', 'Alternative Phone',
            'Business Name', 'Reg. No', 'Tax ID', 'Industry', 'Years in Business', 'Employees',
            'Group Center', 'Street Leader',
            'Address Line 1', 'Address Line 2', 'City', 'State/Province', 'Postal Code', 'Country',
            'Credit Score', 'Credit Rating', 'Risk Category', 'Status', 'Blacklist Reason',
            'KYC Completed', 'KYC Completed At', 'Assigned Officer ID', 'Created At',
        ];

        // 2. Prepare the data row for the specific client
        $data = [
            $client->id,
            ucfirst($client->client_type),
            $client->first_name,
            $client->middle_name,
            $client->last_name,
            $client->other_name,
            ucfirst($client->gender),
            $client->date_of_birth,
            ucfirst($client->marital_status),
            $client->spouse_name,
            $client->national_id,
            $client->email,
            $client->phone,
            $client->alternative_phone,
            $client->business_name,
            $client->business_registration_number,
            $client->tax_identification_number,
            $client->industry_sector,
            $client->years_in_business,
            $client->number_of_employees,
            $client->groupCenter->name ?? 'N/A', // Assuming groupCenter relationship is set up
            $client->is_street_leader ? 'Yes' : 'No',
            $client->address_line1,
            $client->address_line2,
            $client->city,
            $client->state_province,
            $client->postal_code,
            $client->country,
            $client->credit_score,
            $client->credit_rating,
            $client->risk_category,
            ucfirst($client->status),
            $client->blacklist_reason,
            $client->kyc_completed ? 'Yes' : 'No',
            $client->kyc_completed_at,
            $client->assigned_loan_officer_id,
            $client->created_at,
        ];

        // 3. Create a temporary file stream
        $fileName = 'client_' . $client->id . '_' . \Illuminate\Support\Str::slug($client->first_name . ' ' . $client->last_name) . '.csv';
        
        $callback = function() use ($headers, $data)
        {
            $file = fopen('php://output', 'w');
            
            // Write BOM for Excel compatibility with special characters
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Write the headers
            fputcsv($file, $headers);
            
            // Write the data row
            fputcsv($file, $data);

            fclose($file);
        };

        // 4. Return the response as a downloadable file
        return Response::stream($callback, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ]);
    }
}
