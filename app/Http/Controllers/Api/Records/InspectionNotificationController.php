<?php

namespace App\Http\Controllers\Api\Records;

use App\Http\Controllers\Controller;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\Record;
use App\Models\RecordInspectionNotification;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class InspectionNotificationController extends Controller
{
    public function list(Request $request){
        $user_id = $request->user_id;
        $user = User::find($user_id);
        $year = (int) $request->input('year', now()->year);
        $userCreatedYear = Carbon::parse($user->created_at)->year;
        $currentYear = now()->year;

        // Previous Year
        $previousYear = (($year - 1) >= $userCreatedYear) ? $year - 1 : null;

        // Next Year
        $nextYear = (($year + 1) <= $currentYear) ? $year + 1 : null;

        return response()->json([
            'data' => get_due_inspection_counts($user_id, $year),
            'year' => $year,
            'previousYear' => $previousYear,
            'nextYear' => $nextYear
        ], 200);
    }

    public function monthlyList(Request $request){
        $user_id = $request->user_id;
        $monthYear = $request->month_year ?? date('m-Y');

        try {
            $date = Carbon::createFromFormat('m-Y', $monthYear);
        } catch (\Exception $e) {
            $date = now();
        }

        $user = User::find($user_id);
        $date = Carbon::createFromFormat('m-Y', $monthYear);

        $start = $date->copy()->startOfMonth();
        $end   = $date->copy()->endOfMonth();

        $records = Record::query()
            ->where('created_by', $user_id)
            ->whereNotNull('next_inspection_date')
            ->whereBetween('next_inspection_date', [$start, $end])
            ->whereIn('job_form_id', [6, 9])
            ->with([
                'form',
                'customer',
                'property',
                'billing',
                'inspectionNotifications'
            ])

            ->orderBy('next_inspection_date', 'asc')
            ->get();

        $events = [];

        foreach ($records as $record) {
            $notification = $record->inspectionNotifications
                ->where('inspection_date', $record->next_inspection_date)
                ->first();

            $emailSent = $notification ? true : false;
            $emailSentDate = $notification->created_at ?? null;

            $events[] = [
                'id' => $record->id,
                'id' => $record->id,
                'certificate_number' => $record->certificate_number,
                'prev_inspection_date' => $record->inspection_date,
                'next_inspection_date' => $record->next_inspection_date,
                'status' => $record->status,
                'form' => $record->form ?? [],
                'customer' => $record->customer ?? [],
                'property' => $record->property ?? [],
                'billing' => $record->billing ?? [],
                'url' => route('records.show', $record->id),
                'email_sent' => $emailSent,
                'email_sent_date' => $emailSentDate,
            ];
        }
    
        return response()->json([
            'data' => $events,
            'count' => $records->count()
        ], 200);
    }

    public function send(Request $request){
        $user_id = $request->user_id;
        $record_id = $request->record_id;

        $record = Record::with([
            'customer', 
            'property', 
            'customer.address', 
            'customer.contact', 
            'job', 
            'job.property', 
            'job.calendar',
            'job.calendar.slot',
            'form', 
            'user', 
            'user.company'])->find($record_id);

        $user_id = (isset($record->created_by) && $record->created_by > 0 ? $record->created_by : auth()->user()->id);
        $companyPhone = $record->user->companies->pluck('company_phone')->first();
        $companyName = $record->user->companies->pluck('company_name')->first();
        $companyEmail = $record->user->companies->pluck('company_email')->first();
        $customerName = (isset($record->customer->full_name) && !empty($record->customer->full_name) ? $record->customer->full_name : '');
        $customerEmail = (isset($record->customer->contact->email) && !empty($record->customer->contact->email) ? $record->customer->contact->email : '');
        $inspectionDate = date('Y-m-d', strtotime($record->next_inspection_date));
        if(!empty($customerEmail)):
            $subject = 'Reminder: Your '.$record->form->name.' is Due for Renewal';
            $templateTitle = $subject;
            $ccMail[] = $companyEmail;

            $content = '';
            $content .= '<p>Dear '.$customerName.',</p>';

            $content .= '<p>We hope you are well.</p>';
            $content .= '<p>This is a friendly reminder regarding your recent <strong>'.$record->form->name.'</strong> for the property listed below. Based on our records, the document is approaching its review, service, or renewal period, and we recommend arranging the next inspection or follow-up where applicable.</p>';
            $content .= '<p>Property Address: <strong>'.(isset($record->property->full_address) && !empty($record->property->full_address) ? $record->property->full_address : 'N/A').'</strong><br/>
                        Certificate Expiry Date: <strong>'.date('d-m-Y', strtotime($inspectionDate)).'</strong></p>';
            $content .= '<p>Regular servicing and inspections help ensure your gas appliances and systems continue operating safely and efficiently.</p>';
            $content .= '<p>If you would like to arrange an appointment, request a follow-up visit, or simply need advice regarding this record, please feel free to reply to this email or contact our team directly. We’ll be happy to assist you.</p>';
            $content .= '<p>Thank you for trusting us with your property’s safety and maintenance.</p>';

            $content .= '<p>Kind regards,<br/>
                        '.$record->user->name.'<br/>
                        '.$companyName.'<br/>
                        '.$companyPhone.'</p>';
            
            $sendTo = [$customerEmail];
            $configuration = [
                'smtp_host' => env('MAIL_HOST', 'smtp.gmail.com'),
                'smtp_port' => env('MAIL_PORT', '587'),
                'smtp_username' => env('MAIL_USERNAME', 'info@gascertificate.co.uk'),
                'smtp_password' => env('MAIL_PASSWORD', 'PASSWORD'),
                'smtp_encryption' => env('MAIL_ENCRYPTION', 'tls'), 
                
                // 'from_email'    => env('MAIL_FROM_ADDRESS', 'info@gascertificate.co.uk'), 
                // 'from_name'    =>  env('MAIL_FROM_NAME', 'Gas Safe Engineer'), 
            ];
            $configuration['from_name'] = !empty($companyName) ? $companyName : $record->user->name; 
            $configuration['from_email'] = !empty($companyEmail) ? $companyEmail : $record->user->email; 

            $attachmentFiles = [];
            
            GCEMailerJob::dispatch($configuration, $sendTo, new GCESendMail($subject, $content, $attachmentFiles, $templateTitle, 'certificate'), $ccMail); 
            RecordInspectionNotification::updateOrCreate(['record_id' => $record->id, 'inspection_date' => $inspectionDate], [
                'record_id' => $record->id,
                'inspection_date' => $inspectionDate,
                'sent_at' => now(),
                
                'created_by' => $user_id,
            ]);
            return response()->json(['msg' => 'Email reminder successfully sent to the customer.', 'red' => '']);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later.'], 304);
        endif;
    }
}
