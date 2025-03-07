<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Fakers\Countries;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\CompanyBankDetails;

use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
    
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }
    
        // Retrieve the user's company
        $company = Company::with('companyBankDetails')->where('user_id', $user->id)->first();
        // Retrieve bank details for the user's company
        return view('app.company.index', [
            'company' => $company,
        ]);
    }
    public function list()
    {
        //
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = auth()->id();

        $company = Company::create($validatedData);
        $user = User::find(auth()->user()->id);
        $user->first_login = 0;
        $user->save();

        
        $staff = Staff::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password, 
            'status' => 1,
        ]);

        // Attach the staff to the company
        $company->staffs()->attach($staff->id);

        return response()->json(['message' => 'Company created successfully'], 201);
        $company = Company::updateOrCreate(
            ['user_id' => auth()->id()],
            $validatedData
        );

        $company = Company::updateOrCreate(
            ['user_id' => auth()->id()],
            $validatedData
        );

        if ($request->hasFile('company_logo')) {
            if (!empty($company->company_logo)) {
                $oldImagePath = str_replace('storage/', '', $company->company_logo);
                if (Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            }
    
            $file = $request->file('company_logo');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('companies', $filename, 'public');
    
            $company->update(['company_logo' => 'storage/companies/' . $filename]);
        }
    
        // Update or insert bank details
        CompanyBankDetails::updateOrInsert(
            ['company_id' => $company->id], 
            [
                'bank_name'       => $request->bank_name ?? null,
                'name_on_account' => $request->name_on_account ?? null,
                'account_number'  => $request->account_number ?? null,
                'sort_code'       => $request->sort_code ?? null,
                'payment_term'    => $request->payment_term ?? null,
                'updated_at'      => now(),
            ]
        );

        $user = auth()->user();
        if ($user) {
            $user->update(['first_login' => 0]);
        }


        return response()->json([
            'msg' => 'Company Settings updated successfully',
            'company' => $company,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function restore($id)
    {
        //
    }
}
