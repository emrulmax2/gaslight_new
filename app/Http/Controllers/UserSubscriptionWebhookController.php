<?php

namespace App\Http\Controllers;

use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\UserPricingPackage;
use App\Models\UserPricingPackageInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe;
use Illuminate\Support\Number;

class UserSubscriptionWebhookController extends Controller
{
    public function stripeHooks(Request $request){
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');
        $payload = file_get_contents('php://input');
        $sigHeader = $request->header('Stripe-Signature');
        $event = null;
        try {    
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {    
            Log::error('Invalid payload: ' . $e->getMessage());   
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {    
            Log::error('Stripe Webhook Signature Error: ' . $e->getMessage());    
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $configuration = [
            'smtp_host' => env('MAIL_HOST', 'smtp.gmail.com'),
            'smtp_port' => env('MAIL_PORT', '587'),
            'smtp_username' => env('MAIL_USERNAME', 'no-reply@lcc.ac.uk'),
            'smtp_password' => env('MAIL_PASSWORD', 'PASSWORD'),
            'smtp_encryption' => env('MAIL_ENCRYPTION', 'tls'),
            
            'from_email'    => env('MAIL_FROM_ADDRESS', 'no-reply@lcc.ac.uk'),
            'from_name'    =>  env('MAIL_FROM_NAME', 'Gas Safe Engineer'),

        ];

        $message = 'Noting Found!';
        // Handle the event
        switch ($event->type) {
            case 'invoice.payment_failed':
                $theObject = $event->data->object;
                //Log::info(json_encode($theObject->id));
                $invoice_id = $theObject->id;
                $customer_id = $theObject->customer;
                $customer_name = $theObject->customer_name;
                $customer_email = $theObject->customer_email;
                $subscription_id = $theObject->parent->subscription_details->subscription;
                $user_id = $theObject->parent->subscription_details->metadata->user_id;

                $userPackage = UserPricingPackage::where('stripe_customer_id', $customer_id)->orderBy('id', 'DESC')->get()->first();
                if(!isset($userPackage->id) && !empty($subscription_id)):
                    $userPackage = UserPricingPackage::where('stripe_subscription_id', $subscription_id)->orderBy('id', 'DESC')->get()->first();
                elseif(!isset($userPackage->id) && !empty($user_id)):
                    $userPackage = UserPricingPackage::where('user_id', $user_id)->orderBy('id', 'DESC')->get()->first();
                endif;
                if(isset($userPackage->id) && $userPackage->id > 0):
                    $userPackage->update([
                        'active' => 0,
                        'updated_by' => 1
                    ]);
                    $userInvoice = UserPricingPackageInvoice::updateOrCreate(['user_pricing_package_id' => $userPackage->id, 'invoice_id' => $invoice_id], [
                        'user_id' => $userPackage->user_id,
                        'user_pricing_package_id' => $userPackage->id,
                        'invoice_id' => $invoice_id,
                        'start' => date('Y-m-d', strtotime($theObject->period_start)),
                        'end' => date('Y-m-d', strtotime($theObject->period_end)),
                        'status' => 'incomplete',
                        
                        'created_by' => 1,
                    ]);

                    $message = 'Subscription failed status successfully updated.';

                    if($theObject->billing_reason == 'subscription_cycle'):
                        $subject = 'Action Required: Subscription Auto-Renewal Failed';

                        $content = 'Hi '.$customer_name.',<br/><br/>';
                        $content .= '<p>We attempted to renew your subscription, but unfortunately, the payment was unsuccessful. 
                                        This could be due to an expired card, insufficient funds, or updated billing information.</p>';
                        $content .= '<p>To avoid any interruption in your service, please update your payment details as soon as possible by visiting your account.</p>';
                        $content .= '<p>If you\'ve already updated your information, feel free to disregard this message. Need help? Our support team is here for you.</p>';
                        $content .= 'Thanks & Regards<br/>';
                        $content .= 'Gas Safety Engineer';

                        GCEMailerJob::dispatch($configuration, [$customer_email], new GCESendMail($subject, $content, []));
                    elseif($theObject->billing_reason == 'subscription_create'):
                        $subject = 'Payment Issue: We Couldn\'t Process Your Subscription';

                        $content = 'Hi '.$customer_name.',<br/><br/>';
                        $content .= '<p>We tried to process your first subscription payment, but it didn\'t go through. This might be due to an issue with your card or payment method.</p>';
                        $content .= '<p>To activate your subscription and enjoy uninterrupted access, please update your payment details.</p>';
                        $content .= '<p>If you need assistance, our support team is happy to help. Thank you for choosing Gas Safety Engineer!</p>';
                        $content .= 'Thanks & Regards<br/>';
                        $content .= 'Gas Safety Engineer';

                        GCEMailerJob::dispatch($configuration, [$customer_email], new GCESendMail($subject, $content, []));
                    endif;
                endif;
                break;
            case 'invoice.payment_succeeded':
                $theObject = $event->data->object; // contains a \Stripe\PaymentMethod
                Log::info(json_encode($theObject));
                $invoice_id = $theObject->id;
                $customer_id = $theObject->customer;
                $customer_name = $theObject->customer_name;
                $customer_email = $theObject->customer_email;
                $subscription_id = $theObject->parent->subscription_details->subscription;
                $user_id = $theObject->parent->subscription_details->metadata->user_id;

                $userPackage = UserPricingPackage::where('stripe_customer_id', $customer_id)->orderBy('id', 'DESC')->get()->first();
                if(!isset($userPackage->id) && !empty($subscription_id)):
                    $userPackage = UserPricingPackage::where('stripe_subscription_id', $subscription_id)->orderBy('id', 'DESC')->get()->first();
                elseif(!isset($userPackage->id) && !empty($user_id)):
                    $userPackage = UserPricingPackage::where('user_id', $user_id)->orderBy('id', 'DESC')->get()->first();
                endif;
                if(isset($userPackage->id) && $userPackage->id > 0):
                    $userPackage->update([
                        'active' => 1,
                        'start' => date('Y-m-d', strtotime($theObject->period_start)),
                        'end' => date('Y-m-d', strtotime($theObject->period_end)),
                        'price' => ($theObject->total / 100),
                        'updated_by' => 1
                    ]);
                    $userInvoice = UserPricingPackageInvoice::updateOrCreate(['user_pricing_package_id' => $userPackage->id, 'invoice_id' => $invoice_id], [
                        'user_id' => $userPackage->user_id,
                        'user_pricing_package_id' => $userPackage->id,
                        'invoice_id' => $invoice_id,
                        'start' => date('Y-m-d', strtotime($theObject->period_start)),
                        'end' => date('Y-m-d', strtotime($theObject->period_end)),
                        'status' => 'active',
                        
                        'created_by' => 1,
                    ]);

                    if($theObject->billing_reason == 'subscription_cycle'):
                        $subject = 'our Subscription Has Been Successfully Renewed';

                        $content = 'Hi '.$customer_name.',<br/><br/>';
                       
                        $content .= '<p>We\'re happy to let you know that your subscription has been successfully renewed! Your payment was processed, and your access will continue without interruption.</p>';
                        $content .= '<p>';
                            $content .= 'Plan: <strong>'.$userPackage->package->title.'</strong><br/>';
                            $content .= 'Renewal Date: <strong>'.date('Y-m-d', strtotime($theObject->period_start)).' - '.date('Y-m-d', strtotime($theObject->period_end)).'</strong><br/>';
                            $content .= 'Amount Charged: <strong>'.Number::currency(($theObject->total / 100), 'GBP').'</strong>';
                        $content .= '</p>';

                        $content .= '<p>No action is needed on your part. If you have any questions or wish to make changes to your subscription, you can manage from your account.</p>';
                        $content .= '<p>Thank you for being a valued customer!</p>';

                        $content .= 'Thanks & Regards<br/>';
                        $content .= 'Gas Safety Engineer';

                        GCEMailerJob::dispatch($configuration, [$customer_email], new GCESendMail($subject, $content, []));
                    endif;

                    $message = 'Subscription active status successfully updated.';
                endif;
                break;
            case 'customer.subscription.deleted':
                $theObject = $event->data->object; 
                Log::info(json_encode($theObject));

                $subscription_id = $theObject->id;
                $customer_id = $theObject->customer;
                $user_id = $theObject->metadata->user_id;

                $userPackage = UserPricingPackage::where('stripe_subscription_id', $subscription_id)->orderBy('id', 'DESC')->get()->first();
                if(!isset($userPackage->id) && !empty($subscription_id)):
                    $userPackage = UserPricingPackage::where('stripe_customer_id', $customer_id)->orderBy('id', 'DESC')->get()->first();
                elseif(!isset($userPackage->id) && !empty($user_id)):
                    $userPackage = UserPricingPackage::where('user_id', $user_id)->orderBy('id', 'DESC')->get()->first();
                endif;
                if(isset($userPackage->id) && $userPackage->id > 0):
                    $userPackage->update([
                        'active' => 0,
                        'cancellation_requested' => 0, 
                        'requested_by' => null, 
                        'requested_at' => null,
                        'updated_by' => 1
                    ]);

                    if(!empty($user_id) && $user_id > 0):
                        $subject = 'Your Subscription Has Been Canceled';

                        $content = 'Hi '.$userPackage->user->name.',<br/><br/>';
                       
                        $content .= '<p>We\'re confirming that your subscription to '.$userPackage->package->title.' has been successfully canceled. Your access will remain active until the end of your current billing period, which ends on '.date('jS F, Y', strtotime($userPackage->end)).'.</p>';
                        $content .= '<p>If this was a mistake or you change your mind, you can easily reactivate your subscription anytime from your portal.</p>';
                        $content .= '<p>We\'re grateful for the time you spent with us and hope to see you again in the future. If you have any questions or feedback, feel free to reach out.</p>';
                        
                        $content .= 'Thanks & Regards<br/>';
                        $content .= 'Gas Safety Engineer';

                        GCEMailerJob::dispatch($configuration, [$userPackage->user->email], new GCESendMail($subject, $content, []));
                    endif;

                    $message = 'Subscription successfully cacelled.';
                endif;
                break;
            default:
                echo 'Received unknown event type ' . $event->type;
                break;
        }

        return response()->json(['message' => $message], 200);
    }
}

