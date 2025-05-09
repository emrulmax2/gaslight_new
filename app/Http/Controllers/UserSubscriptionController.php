<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserPricingPackage;
use App\Models\UserPricingPackageInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;
use Exception;
use Stripe;

class UserSubscriptionController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        $user_company_id = (isset($user->companies[0]->id) && $user->companies[0]->id > 0 ? $user->companies[0]->id : 0);

        $users = User::leftJoin('company_staff', 'users.id', '=', 'company_staff.user_id')
                ->leftJoin('companies', 'company_staff.company_id', '=', 'companies.id')
                ->select('users.*', 'companies.company_name as company_name','companies.id as company_id')
                ->where('company_staff.company_id', $user_company_id)
                ->orderBy('name', 'ASC')->get();
        return view('app.settings.subscription.index', [
            'title' => 'User Subscription Manager - Gas Certificate APP',
            'breadcrumbs' => [
                ['label' => 'User Subscription Manager', 'href' => 'javascript:void(0);'],
            ],
            'users' => $users
        ]);
    }


    public function list(Request $request)
    {
        //$querystr = isset($request->querystr) ? $request->querystr : '';
        $user = (isset($request->user) && !empty($request->user) ? $request->user : 0);
        $status = (isset($request->status) && !empty($request->status) ? $request->status : '');

        
        $sorters = (isset($request->sorters) && !empty($request->sorters) ? $request->sorters : array(['field' => 'id', 'dir' => 'DESC']));
        $sorts = [];
        foreach($sorters as $sort):
            $sorts[] = $sort['field'].' '.$sort['dir'];
        endforeach;

        $query = UserPricingPackageInvoice::with('user', 'package')->orderByRaw(implode(',', $sorts));
        if ($user > 0): $query->where('user_id', $user); endif;
        if (!empty($status)): $query->where('status', $status); endif;

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
                    "name" => $list->user->name,
                    'email' =>$list->user->email,
                    'package' => (isset($list->package->package->title) ? $list->package->package->title : ''),
                    'price' => (isset($list->package->price) && $list->package->price > 0 ? Number::currency($list->package->price, 'GBP') : Number::currency(0, 'GBP')),
                    'status' => $list->status,
                    'start' => (isset($list->start) && !empty($list->start) ? date('jS F, Y', strtotime($list->start)) : ''),
                    'end' => (isset($list->end) && !empty($list->end) ? date('jS F, Y', strtotime($list->end)) : ''),
                ];
                $i++;
                
            endforeach;
        endif;
        return response()->json(['last_page' => $last_page,'current_page'=> $page*1 , 'data' => $data]); 
    }

    public function downloadInvoice(UserPricingPackageInvoice $inv){
        $inv->load(['user', 'package']);

        $stripe = new \Stripe\StripeClient(env("STRIPE_SECRET"));
        $invoice = $stripe->invoices->retrieve($inv->invoice_id);
        //dd($invoice);

        $report_title = 'Invoice '.$invoice->number;
        $PDFHTML = '';
        $PDFHTML .= '<html>';
            $PDFHTML .= '<head>';
                $PDFHTML .= '<title>'.$report_title.'</title>';
                $PDFHTML .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
                $PDFHTML .= '<style>
                                *{border-width: 0; border-style: solid;border-color: #e5e7eb;}
                                body{font-family: Tahoma, sans-serif; font-size: 0.875rem; line-height: 1.25rem; color: #475569; padding-top: 0;}
                                table{margin-left: 0px; width: 100%; border-collapse: collapse; text-indent: 0;border-color: inherit;}
                                figure{margin: 0;}
                                @media print{  .no-page-break { page-break-inside: avoid; } .page-break-before { page-break-before: always; } .page-break-after { page-break-after: always; } }
                                /*@page{margin: .375rem;}*/

                                .text-center{text-align: center;}
                                .text-left{text-align: left;}
                                .text-right{text-align: right;}
                                .align-top{vertical-align: top;}
                                .align-middle{vertical-align: middle;}

                                .font-medium{font-weight: bold; }
                                .font-bold{font-weight: bold;}
                                .font-sm{font-size: 12px;}
                                .text-2xl{font-size: 1.5rem;}
                                .text-xl{font-size: 1rem;}
                                .text-10px{font-size: 10px;}
                                .text-11px{font-size: 11px;}
                                .text-12px{font-size: 12px;}
                                .text-13px{font-size: 13px;}
                                .text-sm {font-size: 0.875rem;line-height: 1.25rem;}
                                .leading-1{line-height: 1;}
                                .leading-none{line-height: 1;}
                                .leading-20px{line-height: 20px;}
                                .leading-28px{line-height: 28px;}
                                .leading-none {line-height: 1;}
                                .leading-1-3{line-height: 1.3;}
                                .leading-1-2{line-height: 1.2;}
                                .leading-1-1{line-height: 1.1;}
                                .leading-1-5{line-height: 1.5;}
                                .tracking-normal {letter-spacing: 0em;}
                                .text-primary{color: #164e63;}
                                .text-slate-400{color: #94a3b8;}
                                .text-white{color: #FFF;}
                                .uppercase {text-transform: uppercase;}
                                .whitespace-nowrap{white-space: nowrap;}
                                
                                .w-auto{width: auto;}
                                .w-28{width: 7rem;}
                                .w-full{width: 100%;}
                                .w-50-percent, .w-half{width: 50%;}
                                .w-25-percent{width: 25%;}
                                .w-41-percent{width: 41%;}
                                .w-20-percent{width: 20%;}
                                .w-col2{width: 16.666666%;}
                                .w-col4{width: 33.333333%;}
                                .w-col5{width: 41.666666%;}
                                .w-col7{width: 58.333333%;}
                                .w-col8{width: 66.666666%;}
                                .w-col9{width: 75%;}
                                .w-col3{width: 25%;}
                                .w-32 {width: 8rem;}
                                .w-105px{width: 105px;}
                                .w-110px{width: 110px;}
                                .w-100px{width: 100px;}
                                .w-115px{width: 115px;}
                                .w-36px{width: 36px;}
                                .w-130px{width: 130px;}
                                .w-140px{width: 140px;}
                                .w-70px{width: 70px;}
                                .w-60px{width: 60px;}
                                .h-auto{height: auto;}
                                .h-29px{height: 29px;}
                                .h-94px{height: 94px;}
                                .h-35px{height: 35px;}
                                .h-60px{height: 60px;}
                                .h-70px{height: 70px;}
                                .h-80px{height: 80px;}
                                .h-100px{height: 100px;}
                                .h-112px{height: 112px;}
                                .h-25px{height: 25px;}
                                .h-45px{height: 45px;}
                                .h-50px{height: 50px;}
                                .h-30px{height: 30px;}
                                .h-83px{height: 83px;}

                                .pt-0{padding-top: 0;}
                                .pr-0{padding-right: 0;}
                                .pb-0{padding-bottom: 0;}
                                .pl-0{padding-left: 0;}
                                .p-0{padding: 0;}
                                .p-25{padding: 0.625rem;}
                                .p-3{padding: 0.75rem;}
                                .p-5{padding: 1.25rem;}
                                .py-05{padding-top: 0.125rem;padding-bottom: 0.125rem;}
                                .py-025{padding-top: 0.0625rem;padding-bottom: 0.0625rem;}
                                .py-1{padding-top: 0.25rem;padding-bottom: 0.25rem;}
                                .py-1-5{padding-top: 0.375rem;padding-bottom: 0.375rem;}
                                .py-2{padding-top: 0.5rem;padding-bottom: 0.5rem;}
                                .py-3{padding-top: 0.75rem;padding-bottom: 0.75rem;}
                                .px-5{padding-left: 1.25rem;padding-right: 1.25rem;}
                                .px-2{padding-left: 0.5rem;padding-right: 0.5rem;}
                                .px-1{padding-left: 0.25rem;padding-right: 0.25rem;}
                                .pt-1{padding-top: 0.25rem;}
                                .pt-1-5{padding-top: 0.375rem;}
                                .pt-2{padding-top: 0.5rem;}
                                .pr-2{padding-right: 0.5rem;}
                                .pr-1{padding-right: 0.25rem;}
                                .pl-1{padding-left: 0.25rem;}
                                .pl-2{padding-left: 0.5rem;}
                                .pb-1{padding-bottom: 0.25rem;}
                                .pb-2{padding-bottom: 0.5rem;}
                                .pt-05{padding-top: 0.125rem;}
                                .pb-05{padding-bottom: 0.125rem;}
                                .mb-05{margin-bottom: 0.25rem;}
                                .mb-1{margin-bottom: 0.5rem;}
                                .mt-1-5{margin-top: 0.375rem;}
                                .mb-2{margin-bottom: 0.5rem;}
                                .mt-2{margin-top: 0.5rem;}
                                .mt-3{margin-top: 0.75rem;}
                                .mt-0{margin-top: 0;}
                                .mb-0{margin-bottom: 0;}
                                .m-2{margin: .5rem;}
                                .mr-1{margin-right: .25rem;}

                                .bg-danger{ background: #b91c1c; }
                                .bg-warning{ background: #f59e0b; }
                                .bg-primary{ background: #164e63; }
                                .bg-white{background: #FFF;}
                                .bg-readonly{ background-color: #f1f5f9;}
                                .bg-light-2{background-color: #D4EFFB;}
                                .bordered{border-width: 1px;}
                                .border-none {border-style: none;}
                                .border-t{border-top-width: 1px;}
                                .border-t-0{border-top-width: 0;}
                                .border-r{border-right-width: 1px;}
                                .border-r-0{border-right-width: 0;}
                                .border-b{border-bottom-width: 1px;}
                                .border-b-0{border-bottom-width: 0;}
                                .border-l{border-left-width: 1px;}
                                .border-0{border-left-width: 0;}
                                .border-b-1{border-bottom-width: 1px;}
                                .border-l-sec{border-left-color: #1d6a87 !important;}
                                .border-r-sec{border-right-color: #1d6a87 !important;}
                                .border-b-sec{border-bottom-color: #1d6a87 !important;}
                                .border-t-sec{border-top-color: #1d6a87 !important;}
                                .border-slate-200 {border-color: #e2e8f0;}
                                .border-primary{border-color: #164e63;}
                                .border-b-white{border-bottom-color: #FFF;}
                                .rounded-none{border-radius: 0px;}
                                
                                .inline-block {display: inline-block;}
                            </style>';
            $PDFHTML .= '</head>';

            $PDFHTML .= '<body>';
                $PDFHTML .= '<table>';
                    $PDFHTML .= '<tbody>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="p-0 text-2xl leading-1 font-bold" style="padding-bottom: 40px;">Invoice</td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="p-0" style="padding-bottom: 40px;">';
                                $PDFHTML .= '<table style="width: 300px;">';
                                    $PDFHTML .= '<tr>';
                                        $PDFHTML .= '<td class="p-0 font-medium w-130px">Invoice number</td>';
                                        $PDFHTML .= '<td class="p-0 font-medium">'.$invoice->number.'</td>';
                                    $PDFHTML .= '</tr>';
                                    $PDFHTML .= '<tr>';
                                        $PDFHTML .= '<td class="p-0 font-medium w-130px">Date of issue</td>';
                                        $PDFHTML .= '<td class="p-0 font-medium">'.date('jS F, Y', $invoice->created).'</td>';
                                    $PDFHTML .= '</tr>';
                                    if(isset($invoice->due_date) && !empty($invoice->due_date)):
                                        $PDFHTML .= '<tr>';
                                            $PDFHTML .= '<td class="p-0 font-medium w-130px">Date due</td>';
                                            $PDFHTML .= '<td class="p-0 font-medium">'.(isset($invoice->due_date) && !empty($invoice->due_date) ? date('jS F, Y', strtotime($invoice->due_date)) : date('jS F, Y', $invoice->created)).'</td>';
                                        $PDFHTML .= '</tr>';
                                    endif;
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                        $PDFHTML .= '</tr>';

                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<td class="p-0">';
                                $PDFHTML .= '<table>';
                                    $PDFHTML .= '<tr>';
                                        $PDFHTML .= '<td class="w-col2 p-0 align-top">';
                                            $PDFHTML .= '<div class="font-medium leading-1 mb-2">Gas Engineer App</div>';
                                            $PDFHTML .= '<div class="leading-1">United Kingdom</div>';
                                        $PDFHTML .= '</td>';
                                        $PDFHTML .= '<td class="w-col3 p-0 align-top">';
                                            $PDFHTML .= '<div class="font-medium leading-1 mb-2">Bill To</div>';
                                            $PDFHTML .= '<div class="leading-1">'.$inv->user->name.'</div>';
                                            $PDFHTML .= '<div class="leading-1">'.$inv->user->email.'</div>';
                                        $PDFHTML .= '</td>';
                                    $PDFHTML .= '</tr>';
                                $PDFHTML .= '</table>';
                            $PDFHTML .= '</td>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                $PDFHTML .= '<table style="margin-top: 70px;">';
                    $PDFHTML .= '<thead>';
                        $PDFHTML .= '<tr>';
                            $PDFHTML .= '<th class="text-left border-b text-12px pt-0 pb-2">Description</th>';
                            $PDFHTML .= '<th class="text-right border-b text-12px w-100px pt-0 pb-2">Qty</th>';
                            $PDFHTML .= '<th class="text-right border-b text-12px w-100px pt-0 pb-2">Unit Price</th>';
                            $PDFHTML .= '<th class="text-right border-b text-12px w-100px pt-0 pb-2">Amount</th>';
                        $PDFHTML .= '</tr>';
                    $PDFHTML .= '</thead>';
                    $PDFHTML .= '<tbody>';
                        foreach($invoice->lines->data as $line):
                            $currency = (isset($line->currency) && !empty($line->currency) ? strtoupper($line->currency) : 'GBP');
                            $unit_amount = (isset($line->price->unit_amount) && !empty($line->price->unit_amount) ? $line->price->unit_amount / 100 : 0);
                            $amount = (isset($line->amount) && !empty($line->amount) ? $line->amount / 100 : 0);
                            $PDFHTML .= '<tr>';
                                $PDFHTML .= '<td class="text-left border-b text-12px pt-2 pb-2 align-middle leading-1-3">';
                                    $PDFHTML .= 'Gas Subscription<br/>';
                                    $PDFHTML .= date('jS F, Y', $line->period->start).' - '.date('jS F, Y', $line->period->end);
                                $PDFHTML .= '</td>';
                                $PDFHTML .= '<td class="text-right border-b text-12px w-100px pt-2 pb-2 align-middle leading-1">'.$line->quantity.'</td>';
                                $PDFHTML .= '<td class="text-right border-b text-12px w-100px pt-2 pb-2 align-middle leading-1">'.Number::currency($unit_amount, $currency).'</td>';
                                $PDFHTML .= '<td class="text-right border-b text-12px w-100px pt-2 pb-2 align-middle leading-1">'.Number::currency($amount, $currency).'</td>';
                            $PDFHTML .= '</tr>';
                        endforeach;
                    $PDFHTML .= '</tbody>';
                $PDFHTML .= '</table>';

                $subTotal = (isset($invoice->subtotal) && $invoice->subtotal > 0 ? $invoice->subtotal / 100 : 0);
                $total = (isset($invoice->total) && $invoice->total > 0 ? $invoice->total / 100 : 0);
                $PDFHTML .= '<table style="margin-top: 50px;">';
                    $PDFHTML .= '<tr>';
                        $PDFHTML .= '<td class="w-col8 p-0"></td>';
                        $PDFHTML .= '<td class="w-col4 p-0">';
                            $PDFHTML .= '<table>';
                                $PDFHTML .= '<tbody>';
                                    $PDFHTML .= '<tr>';
                                        $PDFHTML .= '<td class="w-half p-0 border-t text-13px py-1">Subtotal</td>';
                                        $PDFHTML .= '<td class="w-half p-0 border-t text-right text-13px py-1">'.Number::currency($subTotal, $invoice->currency).'</td>';
                                    $PDFHTML .= '</tr>';
                                    $PDFHTML .= '<tr>';
                                        $PDFHTML .= '<td class="w-half p-0 border-t text-13px py-1">Total</td>';
                                        $PDFHTML .= '<td class="w-half p-0 border-t text-right text-13px py-1">'.Number::currency($total, $invoice->currency).'</td>';
                                    $PDFHTML .= '</tr>';
                                $PDFHTML .= '</tbody>';
                            $PDFHTML .= '</table>';
                        $PDFHTML .= '</td>';
                    $PDFHTML .= '</tr>';
                $PDFHTML .= '</table>';

            $PDFHTML .= '</body>';
        $PDFHTML .= '</html>';

        $fileName = 'Invoice_'.$invoice->number.'.pdf';
        $pdf = PDF::loadHTML($PDFHTML)->setOption(['isRemoteEnabled' => true])
            ->setPaper('a4', 'portrait')//portrait landscape
            ->setWarnings(false);
        return $pdf->download($fileName);
    }
}
