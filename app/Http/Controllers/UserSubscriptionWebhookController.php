<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;

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

        // Handle the event
        switch ($event->type) {
            case 'invoice.payment_failed':
                $theObject = $event->data->object; // contains a \Stripe\PaymentIntent
                Log::info(json_encode($theObject->id));
                break;
            case 'invoice.payment_succeeded':
                $theObject = $event->data->object; // contains a \Stripe\PaymentMethod
                Log::info(json_encode($theObject));
                break;
            case 'customer.subscription.deleted':
                $theObject = $event->data->object; 
                Log::info(json_encode($theObject));
                break;
            default:
                echo 'Received unknown event type ' . $event->type;
                break;
        }

        return response()->json(['message' => 'Success!'], 200);
    }
}

