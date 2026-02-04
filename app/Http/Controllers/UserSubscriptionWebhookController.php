<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SubscriptionService;
use Exception;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use UnexpectedValueException;

class UserSubscriptionWebhookController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService){
        $this->subscriptionService = $subscriptionService;
    }

    public function stripeHooks(Request $request){
        $endpointSecret = config('services.stripe.hook_secret');
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

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
            Log::info('Stripe webhook validated', ['type' => $event->type]);
            
        } catch (UnexpectedValueException $e) {
            Log::error('Invalid Stripe webhook payload', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Invalid Stripe webhook signature', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (Exception $e) {
            Log::error('Stripe webhook error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 400);
        }

        $this->subscriptionService->handleWebhookEvent($event->toArray());
        
        return response()->json(['success' => true]);
    }
}
