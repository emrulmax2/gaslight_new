<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\Company;
use App\Models\CompanyBankDetails;
use App\Models\Customer;
use App\Models\CustomerContactInformation;
use App\Models\CustomerJob;
use App\Models\CustomerJobCalendar;
use App\Models\CustomerProperty;
use App\Models\CustomerPropertyOccupant;
use App\Models\Invoice;
use App\Models\InvoiceOption;
use App\Models\InvoicePayment;
use App\Models\JobFormEmailTemplate;
use App\Models\JobFormEmailTemplateAttachment;
use App\Models\JobFormPrefixMumbering;
use App\Models\Quote;
use App\Models\QuoteOption;
use App\Models\Record;
use App\Models\RecordOption;
use App\Models\User;
use App\Models\UserPricingPackage;
use App\Models\UserPricingPackageInvoice;
use App\Models\UserReferralCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Number;

class UserController extends Controller
{
    public function update(UpdateUserRequest $request, User $user)
    {
        //password data will not passed here if it is empty
        if($request->input('password') !== null) {
            $hashPassword = Hash::make($request->input('password'));
            $user->password = $hashPassword;
        } else {
            $user->password = $user->password;
        }
        //remove request password from request    
        $request->offsetUnset('password');

        $user->update($request->all());
        if($user->wasChanged()) {

            return response()->json(['message' => 'User updated successfully'], 200);
        }

        return response()->json(['message' => 'User Couldn\'t Updated'], 304);
    }


    public function list(Request $request)
    {
               
        $queryStr = (isset($request->querystr) && !empty($request->querystr) ? $request->querystr : '');
        
        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = User::with('company', 'userpackage')->orderByRaw(implode(',', $sorts));
        if(!empty($queryStr)):
            $query->where('name','LIKE','%'.$queryStr.'%');
        endif;

        $total_rows = $query->count();
        $page = (isset($request->page) && $request->page > 0 ? $request->page : 0);
        $perpage = (isset($request->size) && $request->size == 'true' ? $total_rows : ($request->size > 0 ? $request->size : 10));
        $last_page = $total_rows > 0 ? ceil($total_rows / $perpage) : '';
        
        $limit = $perpage;
        $offset = ($page > 0 ? ($page - 1) * $perpage : 0);

        $Query= $query->skip($offset)
            ->take($limit)
            ->get();

        $data = array();

        if(!empty($Query)):
            $i = 1;
            foreach($Query as $list):
             
                    $data[] = [
                        'id' => $list->id,
                        'sl' => $i,
                        "name" => $list->name,
                        'company_name' => isset($list->company) ? $list->company->company_name : '',
                        'company_id' => isset($list->company) ? $list->company->id : '',
                        'email' => $list->email ?? '',
                        'mobile' => $list->mobile ?? '',
                        'status' => $list->active ?? 0,
                        'deleted_at' => isset($list->deleted_at) ? $list->deleted_at : NULL,
                        'package' => $list->userpackage->package->title ?? '',
                        'price' => isset($list->userpackage->price) && $list->userpackage->price > 0 ? Number::currency($list->userpackage->price, 'GBP') : Number::currency(0, 'GBP'),
                        'next_renew' => isset($list->userpackage->end) && !empty($list->userpackage->end) ? date('jS F, Y', strtotime($list->userpackage->end)) : '',
                        'impersonate_url' => route('impersonate', $list->id),
                    ];
                    $i++;
                

                    
            endforeach;
        endif;
        return response()->json(['last_page' => $last_page, 'data' => $data]); 
    }

    public function delete($user_id){
        $company_id = '';
        $records = Record::where('created_by', $user_id)->pluck('id')->unique()->toArray();
        $invoices = Invoice::where('created_by', $user_id)->pluck('id')->unique()->toArray();
        $quotes = Quote::where('created_by', $user_id)->pluck('id')->unique()->toArray();
        $properties = CustomerProperty::where('created_by', $user_id)->pluck('id')->unique()->toArray();
        $customers = Customer::where('created_by', $user_id)->pluck('id')->unique()->toArray();
        Record::where('created_by', $user_id)->forceDelete();
        RecordOption::whereIn('record_id', $records)->forceDelete();
        Invoice::where('created_by', $user_id)->forceDelete();
        InvoiceOption::whereIn('invoice_id', $invoices)->forceDelete();
        InvoicePayment::whereIn('invoice_id', $invoices)->forceDelete();
        Quote::where('created_by', $user_id)->forceDelete();
        QuoteOption::whereIn('quote_id', $quotes)->forceDelete();
        CustomerJob::where('created_by', $user_id)->forceDelete();
        CustomerProperty::where('created_by', $user_id)->forceDelete();
        CustomerPropertyOccupant::whereIn('customer_property_id', $properties)->forceDelete();
        Customer::where('created_by', $user_id)->forceDelete();
        CustomerContactInformation::whereIn('customer_id', $customers)->forceDelete();
        CustomerJobCalendar::whereIn('customer_id', $customers)->forceDelete();
        $company = DB::table('company_staff')->where('user_id', $user_id)->first();
        DB::table('company_staff')->where('user_id', $user_id)->delete();
        if(isset($company->id) && $company->id > 0):
            $rows = DB::table('company_staff')->where('company_id', $company->id)->get();
            if($rows->count() == 0):
                Company::where('id', $company->id)->forceDelete();
                CompanyBankDetails::where('company_id', $company->id)->forceDelete();
            endif;
        endif;
        JobFormPrefixMumbering::where('user_id', $user_id)->forceDelete();
        JobFormEmailTemplate::where('created_by', $user_id)->forceDelete();
        JobFormEmailTemplateAttachment::where('created_by', $user_id)->forceDelete();
        UserPricingPackage::where('user_id', $user_id)->forceDelete();
        UserPricingPackageInvoice::where('user_id', $user_id)->forceDelete();
        UserReferralCode::where('user_id', $user_id)->forceDelete();
        User::where('id', $user_id)->forceDelete();

        return response()->json(['msg' => 'Userdata has been successfully deleted.']);
    }
}
