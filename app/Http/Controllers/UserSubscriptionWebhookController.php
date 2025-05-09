<?php

namespace App\Http\Controllers;

use App\Models\UserPricingPackage;
use App\Models\UserPricingPackageInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe;

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

        $message = 'Noting Found!';
        // Handle the event
        switch ($event->type) {
            case 'invoice.payment_failed':
                $theObject = $event->data->object;
                //Log::info(json_encode($theObject->id));
                $invoice_id = $theObject->id;
                $customer_id = $theObject->customer;
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
                        'updated_by' => Auth::user()->id
                    ]);
                    $userInvoice = UserPricingPackageInvoice::updateOrCreate(['user_pricing_package_id' => $userPackage->id, 'invoice_id' => $invoice_id], [
                        'user_id' => $userPackage->user_id,
                        'user_pricing_package_id' => $userPackage->id,
                        'invoice_id' => $invoice_id,
                        'start' => date('Y-m-d', strtotime($theObject->period_start)),
                        'end' => date('Y-m-d', strtotime($theObject->period_end)),
                        'status' => 'incomplete',
                        
                        'created_by' => Auth::user()->id,
                    ]);

                    $message = 'Subscription failed status successfully updated.';
                endif;
                break;
            case 'invoice.payment_succeeded':
                $theObject = $event->data->object; // contains a \Stripe\PaymentMethod
                Log::info(json_encode($theObject));
                $invoice_id = $theObject->id;
                $customer_id = $theObject->customer;
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
                        'start' => date('Y-m-d', strtotime($theObject->period_start)),
                        'end' => date('Y-m-d', strtotime($theObject->period_end)),
                        'price' => ($theObject->total / 100),
                        'updated_by' => Auth::user()->id
                    ]);
                    $userInvoice = UserPricingPackageInvoice::updateOrCreate(['user_pricing_package_id' => $userPackage->id, 'invoice_id' => $invoice_id], [
                        'user_id' => $userPackage->user_id,
                        'user_pricing_package_id' => $userPackage->id,
                        'invoice_id' => $invoice_id,
                        'start' => date('Y-m-d', strtotime($theObject->period_start)),
                        'end' => date('Y-m-d', strtotime($theObject->period_end)),
                        'status' => 'incomplete',
                        
                        'created_by' => Auth::user()->id,
                    ]);

                    $message = 'Subscription failed status successfully updated.';
                endif;
                break;
            case 'customer.subscription.deleted':
                $theObject = $event->data->object; 
                Log::info(json_encode($theObject));
                break;
            default:
                echo 'Received unknown event type ' . $event->type;
                break;
        }

        return response()->json(['message' => $message], 200);
    }
}

