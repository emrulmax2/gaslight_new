<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\User;

use App\Fakers\Countries;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('app.company.index',[
            'countries' => Countries::fakeCountries(),
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
        $validatedData['user_id'] = auth()->user()->id; // Assuming you want to set the user_id to the currently authenticated user

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
