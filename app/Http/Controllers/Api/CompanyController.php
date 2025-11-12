<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\CompanyBankDetails;
use App\Models\RegisterBody;
use Creagia\LaravelSignPad\Signature;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {

        $validatedData = $request->validated();
        $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
        $decodedData = base64_decode($signatureData, true);

        if (!$request->has('signature_file') && strlen($decodedData) <= 2621) {
            throw ValidationException::withMessages([
                'signature' => ['Either a signature file or drawn signature is required.']
            ]);
        }

        $validatedData['user_id'] = $request->user()->id;
        $validatedData['gas_safe_registration_no'] = (isset($request->gas_safe_registration_no) && !empty($request->gas_safe_registration_no)) ? $request->gas_safe_registration_no : null;
        $validatedData['gas_safe_id_card'] = (isset($request->gas_safe_id_card) && !empty($request->gas_safe_id_card)) ? $request->gas_safe_id_card : null;
        
        $company = Company::create($validatedData);
        $user = User::find($request->user()->id);
        $user->first_login = 0;
        $user->save();

        $company->users()->attach($user->id);

        if ($request->has('signature_file')) {
            $file = $request->file('signature_file');
            $newFilePath = 'signatures/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
    
            Storage::disk('public')->put($newFilePath, file_get_contents($file->getRealPath()));
    
            $signature = new Signature();
            $signature->model_type = User::class;
            $signature->model_id = $user->id;
            $signature->uuid = Str::uuid();
            $signature->filename = $newFilePath;
            $signature->document_filename = null;
            $signature->certified = false;
            $signature->from_ips = json_encode([request()->ip()]);
            $signature->save();
            
        } elseif ($request->has('sign') && $request->input('sign') !== null) {
            $signatureData = str_replace('data:image/png;base64,', '', $request->input('sign'));
            $signatureData = base64_decode($signatureData);
            $imageName = 'signatures/' . Str::uuid() . '.png';
            Storage::disk('public')->put($imageName, $signatureData);

            $signature = new Signature();
            $signature->model_type = User::class;
            $signature->model_id = $user->id;
            $signature->uuid = Str::uuid();
            $signature->filename = $imageName;
            $signature->document_filename = null;
            $signature->certified = false;
            $signature->from_ips = json_encode([request()->ip()]);
            $signature->save();
        }

        return response()->json([
            'message' => 'Company details stored successfully.', 
            'data' => $company
        ], 200);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function getDetails(Company $company)
    {
        $company->load('companyBankDetails');
        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found.',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'company' => $company,
                'registerBodies' => RegisterBody::where('active', 1)->get(),
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {

        $company->load('bank');
        $companyLogoName = (isset($company->company_logo) && !empty($company->company_logo) ? $company->company_logo : '');
        $company->update([
            'company_name' => $request->company_name,
            'vat_number' => $request->vat_number,
            'business_type' => $request->business_type,
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
        ]);

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


        return response()->json([
            'message' => 'Company Settings updated successfully',
            'data' => $company,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found.',
            ], 404);
        }
        $company->delete();

        return response()->json([
            'success' => true,
            'message' => 'Company deleted successfully.',
        ], 200);
    }
    public function getCompanyBankDetails(Request $request)
    {
        $company = Company::where('user_id', $request->user()->id)->first();

        $companyBankDetails = CompanyBankDetails::where('company_id', $company->id)->first();

        if ($companyBankDetails) {
            return response()->json([
                'success' => true,
                'data' => $companyBankDetails,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No bank details found for this company.',
            ], 404);
        }
    }

    public function vatStatusNumber(Request $request){
        $user_id = $request->user()->id;
        $user = User::find($user_id);

        $data = [
            'non_vat_status' => (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? 0 : 1),
            'vat_number' => (isset($user->companies[0]->vat_number) && !empty($user->companies[0]->vat_number) ? $user->companies[0]->vat_number : ''),
        ];


         return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
    }

}
