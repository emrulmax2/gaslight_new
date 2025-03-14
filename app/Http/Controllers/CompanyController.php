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
use App\Models\RegisterBody;
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
            'registerBodies' => RegisterBody::where('active', 1)->get()
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

    public function initialSetup(){
        return view('app.dashboard.initial-setup');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = auth()->id();
        $validatedData['gas_safe_registration_no'] = (isset($request->gas_safe_registration_no) && !empty($request->gas_safe_registration_no)) ? $request->gas_safe_registration_no : null;
        

        $company = Company::create($validatedData);
        $user = User::find(auth()->user()->id);
        $user->first_login = 0;
        $user->gas_safe_id_card = (isset($request->gas_safe_id_card) && !empty($request->gas_safe_id_card) ? $request->gas_safe_id_card : null);
        $user->save();

        
        $staff = Staff::create([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password, 
            'status' => 1,
        ]);

        $company->staffs()->attach($staff->id);
        return response()->json(['msg' => 'Company created successfully.', 'red' => route('company.dashboard')], 200);
        // return response()->json(['message' => 'Company created successfully'], 201);
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
    public function update(UpdateCompanyRequest $request)
    {
        $company = Company::find($request->company_id);
        $companyLogoName = (isset($company->company_logo) && !empty($company->company_logo) ? $company->company_logo : '');
        $companyData = [
            'company_name' => (!empty($request->company_name) ? $request->company_name : null),
            'vat_number' => (!empty($request->company_email) ? $request->company_email : null),
            'business_type' => (!empty($request->business_type) ? $request->business_type : null),
            'company_registration' => ($request->business_type == 'Company' && !empty($request->company_registration) ? $request->company_registration : null),
            'display_company_name' => (!empty($request->display_company_name) && $request->display_company_name > 0 ? $request->display_company_name : 0),

            'gas_safe_registration_no' => (!empty($request->gas_safe_registration_no) ? $request->gas_safe_registration_no : null),
            'registration_no' => (!empty($request->registration_no) ? $request->registration_no : null),
            'register_body_id' => (!empty($request->register_body_id) ? $request->register_body_id : null),
            'registration_body_for_legionella' => (!empty($request->registration_body_for_legionella) ? $request->registration_body_for_legionella : null),
            'registration_body_no_for_legionella' => (!empty($request->registration_body_no_for_legionella) ? $request->registration_body_no_for_legionella : null),

            'company_phone' => (!empty($request->company_phone) ? $request->company_phone : null),
            'company_web_site' => (!empty($request->company_web_site) ? $request->company_web_site : null),
            'company_tagline' => (!empty($request->company_tagline) ? $request->company_tagline : null),
            'company_email' => (!empty($request->company_email) ? $request->company_email : null),

            'company_address_line_1' => (!empty($request->company_address_line_1) ? $request->company_address_line_1 : null),
            'company_address_line_2' => (!empty($request->company_address_line_2) ? $request->company_address_line_2 : null),
            'company_city' => (!empty($request->company_city) ? $request->company_city : null),
            'company_state' => (!empty($request->company_state) ? $request->company_state : null),
            'company_postal_code' => (!empty($request->company_postal_code) ? $request->company_postal_code : null),
            'company_country' => (!empty($request->company_country) ? $request->company_country : null),
        ];
        $companyUpdate = Company::where('id', $company->id)->update($companyData);

        if ($request->hasFile('company_logo')):
            if (!empty($company->company_logo) && Storage::disk('public')->exists('companies/'.$company->id.'/'.$companyLogoName)):
                Storage::disk('public')->delete('companies/'.$company->id.'/'.$companyLogoName);
            endif;

            $document = $request->file('company_logo');
            $imageName = time().'_'.$company->id.'.'.$document->getClientOriginalExtension();
            $path = $document->storeAs('companies/'.$company->id, $imageName, 'public');
            
            $company->update(['company_logo' => $imageName]);
        endif;
    
        
        CompanyBankDetails::updateOrInsert(['company_id' => $company->id], [
            'bank_name'       => $request->bank_name ?? null,
            'name_on_account' => $request->name_on_account ?? null,
            'account_number'  => $request->account_number ?? null,
            'sort_code'       => $request->sort_code ?? null,
            'payment_term'    => $request->payment_term ?? null,
            'updated_at'      => now(),
        ]);


        return response()->json(['msg' => 'Company Settings updated successfully', 'red' => '', ], 200);
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
