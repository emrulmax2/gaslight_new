<?php
// app/Services/SubscriptionService.php

namespace App\Services;

use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\User;
use App\Models\PricingPackage;
use App\Models\StripeWebhookLog;
use App\Models\UserPricingPackage;
use App\Models\UserPricingPackageInvoice;
use Stripe\StripeClient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Number;

class SubscriptionService{
    protected $stripe;
    protected $configuration;
    
    public function __construct(){
        $this->stripe = new StripeClient(config('services.stripe.secret'));
        $this->configuration = [
            'smtp_host' => env('MAIL_HOST', 'smtp.gmail.com'),
            'smtp_port' => env('MAIL_PORT', '587'),
            'smtp_username' => env('MAIL_USERNAME', 'no-reply@lcc.ac.uk'),
            'smtp_password' => env('MAIL_PASSWORD', 'PASSWORD'),
            'smtp_encryption' => env('MAIL_ENCRYPTION', 'tls'),
            
            'from_email'    => env('MAIL_FROM_ADDRESS', 'no-reply@lcc.ac.uk'),
            'from_name'    =>  env('MAIL_FROM_NAME', 'Gas Safe Engineer'),

        ];
    }
    
    
    /**
     * Subscribe to a plan (Monthly/Yearly)
     */
    public function subscribe(User $user, PricingPackage $package, $paymentMethodId, $cardHolderName = null){
        // Check if user already has an active subscription
        $existingSubscription = UserPricingPackage::where('user_id', $user->id)
            ->where('active', 1)->where('pricing_package_id', '>', 1)
            ->first();
            
        if ($existingSubscription) {
            throw new \Exception('You already have an active subscription');
        }
        
        // Create or get Stripe customer
        $stripeCustomer = $this->getOrCreateStripeCustomer($user);
        // Attach payment method
        $this->attachPaymentMethod($stripeCustomer->id, $paymentMethodId);
        
        // Prepare subscription data
        $subscriptionData = [
            'customer' => $stripeCustomer->id,
            'items' => [
                ['price' => $package->stripe_plan]
            ],
            'currency' => 'GBP',
            'metadata' => [
                'billed_to' => $cardHolderName,
                'user_id' => $user->id
            ],
            'payment_behavior' => 'default_incomplete',
            'expand' => ['latest_invoice.payment_intent']
        ];
        
        // Create Stripe subscription
        $stripeSubscription = $this->stripe->subscriptions->create($subscriptionData);
        
        // Get dates from Stripe subscription
        $currentPeriodStart = Carbon::createFromTimestamp($stripeSubscription->current_period_start);
        $currentPeriodEnd = Carbon::createFromTimestamp($stripeSubscription->current_period_end);
        
        // Create local subscription record
        $userSubscription = UserPricingPackage::updateOrCreate(['user_id' => $user->id], [
            'user_id' => $user->id,
            'pricing_package_id' => $package->id,
            'stripe_customer_id' => $stripeCustomer->id,
            'stripe_subscription_id' => $stripeSubscription->id,
            'start' => $currentPeriodStart,
            'end' => $currentPeriodEnd,
            'price' => $package->price,
            'active' => 0,
            'cancellation_requested' => 0,
            'created_by' => $user->id,
            'updated_by' => $user->id
        ]);
        
        // Create invoice record
        if ($stripeSubscription->latest_invoice) {
            $this->createInvoiceRecord($user, $userSubscription, $stripeSubscription);
        }
        
        return [
            'subscription' => $userSubscription,
            'stripe_subscription' => $stripeSubscription,
            'client_secret' => $stripeSubscription->latest_invoice->payment_intent->client_secret ?? null
        ];
    }
    
    /**
     * Upgrade from monthly to yearly plan
     */
    public function upgradeToYearly(User $user, PricingPackage $newPackage){
        $userPackage = UserPricingPackage::where('user_id', $user->id)
                        ->where('active', 1)
                        ->firstOrFail();

        // Get current subscription
        $currentSubscription = $this->stripe->subscriptions->retrieve(
            $userPackage->stripe_subscription_id
        );

        // Create subscription schedule for YEARLY (starts after monthly ends)
        $schedule = $this->stripe->subscriptionSchedules->create([
            'customer' => $userPackage->stripe_customer_id,
            'start_date' => $currentSubscription->end,
            'phases' => [[
                    'items' => [[
                            'price' => $newPackage->stripe_plan,
                            'quantity' => 1,
                    ]],
                    'duration' => [
                        'interval' => 'year',
                        'interval_count' => 1,
                    ],
            ]],
            'metadata' => [
                'action' => 'upgrade',
                'from_package_id' => $userPackage->pricing_package_id,
                'to_package_id' => $newPackage->id,
                'user_id' => $user->id,
            ],
        ]);

        // Cancel current subscription at period end
        $this->stripe->subscriptions->update($userPackage->stripe_subscription_id, [
            'cancel_at_period_end' => true,
        ]);

        // Update DB (NO new row yet)
        $userPackage->update([
            'cancellation_requested' => 1,
            'requested_at' => now(),
            'requested_by' => Auth::user()->id, 
            'upgrade_to' => $newPackage->id,
        ]);

        return [
            'success' => true,
            'subscription' => $currentSubscription,
            'newPackage' => $newPackage,
            'schedule' => $schedule
        ];
    }
    
    /**
     * Cancel/unsubscribe from subscription
     */
    public function cancelSubscription(User $user)
    {
        $userPackage = UserPricingPackage::where('user_id', $user->id)
            ->where('active', 1)
            ->first();

        if (!$userPackage || !$userPackage->stripe_subscription_id) {
            throw new \Exception('No active subscription found');
        }

        $this->stripe->subscriptions->update($userPackage->stripe_subscription_id, [
                'cancel_at_period_end' => true,
                'metadata' => [
                    'cancelled_by' => 'user',
                    'user_id' => $user->id,
                ],
        ]);

        // Mark cancellation intent in DB
        $userPackage->update([
            'cancellation_requested' => 1,
            'requested_by' => $user->id,
            'requested_at' => now()
        ]);

        return true;
    }
    
    /**
     * Get user's current subscription status
     */
    public function getSubscriptionStatus(User $user)
    {
        $subscription = UserPricingPackage::where('user_id', $user->id)
            ->where('active', true)
            ->with(['pricingPackage', 'invoices' => function($query) {
                $query->latest()->limit(5);
            }])
            ->latest()
            ->first();
        
        if (!$subscription) {
            return [
                'has_subscription' => false,
                'message' => 'No active subscription'
            ];
        }
        
        $isActive = $subscription->active && 
                   $subscription->end > now() && 
                   !$subscription->cancellation_requested;
        
        $canUpgrade = $subscription->pricingPackage && 
                     $subscription->pricingPackage->period === 'monthly' && 
                     $isActive;
        
        return [
            'has_subscription' => true,
            'subscription' => $subscription,
            'plan' => $subscription->pricingPackage,
            'is_active' => $isActive,
            'current_period_end' => $subscription->end,
            'days_remaining' => max(0, now()->diffInDays($subscription->end, false)),
            'can_upgrade' => $canUpgrade,
            'can_cancel' => $isActive,
            'cancellation_requested' => $subscription->cancellation_requested,
            'invoices' => $subscription->invoices
        ];
    }
    
    /**
     * Get user's invoices
     */
    public function getUserInvoices(User $user)
    {
        return UserPricingPackageInvoice::where('user_id', $user->id)
            ->with('userPricingPackage.pricingPackage')
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    /**
     * Helper Methods
     */
    private function getOrCreateStripeCustomer(User $user){
        // Check if user already has a Stripe customer
        $existingCustomer = UserPricingPackage::where('user_id', $user->id)
            ->whereNotNull('stripe_customer_id')
            ->first();
            
        if ($existingCustomer && $existingCustomer->stripe_customer_id) {
            try {
                return $this->stripe->customers->retrieve($existingCustomer->stripe_customer_id);
            } catch (\Exception $e) {
                // Customer doesn't exist in Stripe, create new one
            }
        }
        
        // Create new Stripe customer
        return $this->stripe->customers->create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]
        ]);
    }
    
    private function attachPaymentMethod(string $customerId, string $paymentMethodId){
        // Attach payment method
        $this->stripe->paymentMethods->attach($paymentMethodId, [
            'customer' => $customerId
        ]);
        
        // Set as default
        $this->stripe->customers->update($customerId, [
            'invoice_settings' => [
                'default_payment_method' => $paymentMethodId
            ]
        ]);
    }
    
    private function getSubscriptionItemId($subscriptionId)
    {
        $subscription = $this->stripe->subscriptions->retrieve($subscriptionId);
        return $subscription->items->data[0]->id;
    }
    
    private function createInvoiceRecord(User $user, UserPricingPackage $subscription, $stripeSubscription)
    {
        // Get dates from subscription
        $periodStart = Carbon::createFromTimestamp($stripeSubscription->current_period_start);
        $periodEnd = Carbon::createFromTimestamp($stripeSubscription->current_period_end);
        
        // Get invoice status
        $invoice = $stripeSubscription->latest_invoice;
        $status = 'open';
        
        if ($invoice) {
            $status = $invoice->status;
            $invoiceId = $invoice->id;
        } else {
            $invoiceId = uniqid('in_');
        }
        
        return UserPricingPackageInvoice::create([
            'user_id' => $user->id,
            'user_pricing_package_id' => $subscription->id,
            'invoice_id' => $invoiceId,
            'start' => $periodStart,
            'end' => $periodEnd,
            'status' => $status,
            'created_by' => $user->id,
            'updated_by' => $user->id
        ]);
    }
    
    /**
     * Webhook Handlers
     */

    protected function isDuplicateEvent($event){
        return StripeWebhookLog::where(
            'event_id',
            $event['id']
        )->exists();
    }

    protected function storeWebhookEvent($event){
        StripeWebhookLog::create([
            'event_id' => $event['id'],
            'event_type' => $event['type'],
        ]);
    }

    public function handleWebhookEvent(array $event)
    {
        if ($this->isDuplicateEvent($event)) {
            Log::info('Event is already exist.', ['type' => $event['type']]);
            return;
        }

        // store first to prevent race condition
        $this->storeWebhookEvent($event);

        Log::info('Stripe webhook received', ['type' => $event['type']]);
        
        switch ($event['type']) {
            case 'invoice.payment_succeeded':
                $this->handlePaymentSucceeded($event['data']['object']);
                break;
                
            case 'invoice.payment_failed':
                $this->handlePaymentFailed($event['data']['object']);
                break;
                
            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event['data']['object']);
                break;
                
            case 'customer.subscription.created':
                $this->handleSubscriptionCreated($event['data']['object']);
                break;
        }
    }
    
    private function handlePaymentSucceeded($invoice)
    {
        try {
            $invoice_id = $invoice['id'];
            $customer_id = $invoice['customer'];
            $customer_name = $invoice['customer_name'];
            $customer_email = $invoice['customer_email'];

            $subscription_id = $invoice['parent']['subscription_details']['subscription'] ?? null;
            $metadata = $invoice['parent']['subscription_details']['metadata'] ?? [];
            $user_id = $metadata['user_id'] ?? null;

            if (!$user_id) {
                Log::info('User id can not be null', [
                    'subscription_id' => $subscription_id,
                    'invoice_id' => $invoice_id
                ]);
                return;
            }

            if (UserPricingPackageInvoice::where('invoice_id', $invoice_id)->exists()) {
                Log::info('Invoice already exist.', [
                    'subscription_id' => $subscription_id,
                    'invoice_id' => $invoice_id
                ]);
                return;
            }

            $subscription = $this->stripe->subscriptions->retrieve($subscription_id);
            $priceId = $subscription->items->data[0]->price->id;
            $package = PricingPackage::where('stripe_plan', $priceId)->first();

            if(!$package) {
                Log::info('Pricing package not found.', [
                    'stripe_plan' => $priceId,
                    'invoice_id' => $invoice_id
                ]);
                return;
            }

            // Invoice period
            $line = $invoice['lines']['data'][0];
            $periodStart = Carbon::createFromTimestamp($line['period']['start']);
            $periodEnd   = Carbon::createFromTimestamp($line['period']['end']);

            if ($invoice['billing_reason'] === 'subscription_create'):
                // DEACTIVE PREVOUS PACK
                UserPricingPackage::where('user_id', $user_id)
                    ->where('active', 1)
                    ->update([
                        'active' => 0, 
                        'cancellation_requested' => 0, 
                        'requested_by' => null,
                        'requested_at' => null,
                        'upgrade_to' => null,
                        'updated_by' => $user_id,
                    ]);
                // ADD NEW PAC
                $userPackage = UserPricingPackage::create([
                    'user_id' => $user_id,
                    'pricing_package_id' => $package->id,
                    'stripe_customer_id' => $subscription->customer,
                    'stripe_subscription_id' => $subscription->id,
                    'start' => $periodStart->toDateString(),
                    'end' => $periodEnd->toDateString(),
                    'price' => $package->price,
                    'active' => 1,
                    'created_by' => $user_id,
                ]);

                $subject = 'Your subscription is now active';

                $content = '<p>Hi '.$customer_name.',</p>';
                $content .= '<p>Great news — your subscription has been successfully activated! Your payment went through, and you now have full access to your plan.</p>';
                $content .= '<p><strong>Subscription Details:</strong></p>';
                $content .= '<p>';
                    $content .= '<strong>Plan:</strong> '.$package->title.'<br/>';
                    $content .= '<strong>Billing Cycle:</strong>'.$package->period.'<br/>';
                    $content .= '<strong>Amount Paid:</strong> '.Number::currency($package->price, 'GBP').'<br/>';
                    $content .= '<strong>Subscription Start:</strong> '.$periodStart->toDateString().'<br/>';
                    $content .= '<strong>Next Billing Date:</strong> '.$periodEnd->toDateString().'<br/>';
                $content .= '</p>';
                $content .= '<p>You can start using all the features included in your plan right away.</p>';
                $content .= '<p>If you ever need to upgrade, manage billing, or cancel your subscription, you can do so anytime from your account dashboard.</p>';
                $content .= '<p>If you have any questions or need help, just reply to this email — we\'re happy to help.</p>';
                $content .= '<p>Welcome aboard!<br/>Gas Safety Engineer</p>';
            else:
                // RENEWAL PAYMENT
                $userPackage = UserPricingPackage::where('stripe_subscription_id', $subscription->id)->first();

                if ($userPackage) {
                    $userPackage->update([
                        'start' => $periodStart->toDateString(),
                        'end' => $periodEnd->toDateString(),
                        'price' => $package->price,
                        'cancellation_requested' => 0, 
                        'requested_by' => null,
                        'requested_at' => null,
                        'upgrade_to' => null,
                        'updated_by' => $user_id,
                    ]);
                }

                $subject = 'Your subscription is now active';
                    
                $content = '<p>Hi '.$customer_name.',</p>';
                $content .= '<p>This is a quick confirmation that your subscription has been <strong>renewed successfully</strong>. Thank you for continuing with us!</p>';
                $content .= '<p><strong>Subscription Details:</strong></p>';
                $content .= '<p>';
                    $content .= '<strong>Plan:</strong> '.$package->title.'<br/>';
                    $content .= '<strong>Billing Cycle:</strong>'.$package->period.'<br/>';
                    $content .= '<strong>Amount Paid:</strong> '.Number::currency($package->price, 'GBP').'<br/>';
                    $content .= '<strong>Subscription Start:</strong> '.$periodStart->toDateString().'<br/>';
                    $content .= '<strong>Next Billing Date:</strong> '.$periodEnd->toDateString().'<br/>';
                $content .= '</p>';
                $content .= '<p>Your access remains uninterrupted, and you can continue enjoying all the features of your plan.</p>';
                $content .= '<p>You can view invoices or manage your subscription anytime from your dashboard.</p>';
                $content .= '<p>Thanks for staying with us<br/>Gas Safety Engineer</p>';
            endif;

            // Save invoice (for BOTH cases)
            UserPricingPackageInvoice::updateOrCreate(['invoice_id' => $invoice_id], [
                'user_id' => $user_id,
                'user_pricing_package_id' => $userPackage->id,
                'invoice_id' => $invoice_id,
                'start' => $periodStart->toDateString(),
                'end' => $periodEnd->toDateString(),
                'status' => 'paid',
                'created_by' => $user_id,
            ]);

            GCEMailerJob::dispatch($this->configuration, [$customer_email], new GCESendMail($subject, $content, []), ['limon@lcc.ac.uk']);

            Log::info('Payment succeeded for subscription', [
                'subscription_id' => $subscription->id,
                'invoice_id' => $invoice['id']
            ]);
        } catch (\Exception $e) {
            Log::error('Payment succeeded webhook error: ' . $e->getMessage(), [
                'invoice_id' => $invoice['id']
            ]);
        }
    }
    
    private function handlePaymentFailed($invoice){
        if (!$invoice['subscription']) {
            Log::error('Subscription not found.', [
                'invoice_id' => $invoice['id']
            ]);
            return;
        }

        $stripeSubscriptionId = $invoice['subscription'];
        $userPackage = UserPricingPackage::where(
            'stripe_subscription_id',
            $stripeSubscriptionId
        )->first();

        if (!$userPackage) {
            Log::error('User Pricing Package not found.', [
                'invoice_id' => $invoice['id']
            ]);
            return;
        }

        // Convert timestamps safely
        $start = isset($invoice->period_start) ? date('Y-m-d', (int) $invoice->period_start) : null;
        $end = isset($invoice->period_end) ? date('Y-m-d', (int) $invoice->period_end) : null;

        // CREATE OR UPDATE INVOICE
        UserPricingPackageInvoice::updateOrCreate(['invoice_id' => $invoice['id']], [
            'user_id' => $userPackage->user_id,
            'user_pricing_package_id' => $userPackage->id,
            'start' => $start,
            'end' => $end,
            'status' => 'failed',
        ]);

        $user = User::find($userPackage->user_id);
        if($user):
            $subject = 'Payment attempt failed — action may be required';
            $content = '';
            $content .= '<p>Hi '.$user->name.'</p>';
            $content .= '<p>We attempted to process your payment for the '.$userPackage->package->title.' subscription, 
                        but unfortunately the transaction was unsuccessful.</p>';
            $content .= '<p><strong>Payment Details</strong><br/>';
            $content .= '<strong>Plan:</strong>' .$userPackage->package->title.'</strong><br/>';
            $content .= '<strong>Amount Due:</strong> '.Number::currency($invoice['amount_due'] / 100, 'GBP').'</strong><br/>';
            $content .= '<strong>Attempt Number:</strong>'.($invoice['attempt_count'] ?? 1).'</p>';
            $content .= '<p>What you can do now:</p>';
            $content .= '<ol>';
                $content .= '<li>Check your card balance</li>';
                $content .= '<li>Verify card expiry date</li>';
                $content .= '<li>Update your payment method in your dashboard</li>';
            $content .= '</ol>';
            $content .= '<p>If the next payment attempt is successful, your subscription will continue without interruption.</p>';
            $content .= '<p>If you need assistance, please contact our support team.</p>';
            $content .= '<p>Thank you,<br/>Gas Safety Engineer</p>';

            GCEMailerJob::dispatch($this->configuration, [$user->email], new GCESendMail($subject, $content, []), ['limon@lcc.ac.uk']);
        endif;

        Log::error('Subscription payment faild successfully noted.', [
            'invoice_id' => $invoice['id']
        ]);
        return;
    }
    
    private function handleSubscriptionDeleted($subscriptionData)
    {
        try {
            $stripeSubscriptionId = $subscriptionData['id'];
            $metaUserId = $subscription->metadata['user_id'] ?? null;

            $query = UserPricingPackage::where('stripe_subscription_id', $stripeSubscriptionId);
            if ($metaUserId): $query->where('user_id', $metaUserId); endif;
            $userPackage = $query->first();

            if (!$userPackage) {
                Log::error('User package not found!');
            }

            // Final deactivation (this is the correct place)
            $userPackage->update([
                'active' => 0,
                'end' => Carbon::createFromTimestamp($subscriptionData['current_period_end']),
                'cancellation_requested' => 0,
                'requested_by' => null,
                'requested_at' => null,
                'upgrade_to' => null,
            ]);
                
            $subject = 'Your subscription has ended';
            $content = '';
            $content .= '<p>Hi '.$userPackage->user->name.',</p>';
            $content .= '<p>We wanted to let you know that your '.$userPackage->package->title.' subscription has now ended.</p>';
            $content .= '<p><strong>Subscription Summary</strong><br/>';
            $content .= '<strong>Plan:</strong> '.$userPackage->package->title.'<br/>';
            $content .= '<strong>Billing Cycle:</strong> '.$userPackage->package->period.'<br/>';
            $content .= '<strong>End Date:</strong> '.Carbon::createFromTimestamp($subscriptionData['current_period_end']).'</p>';
            $content .= '<p>As a result, access to subscription features has been deactivated. If this was intentional, no further action is needed.</p>';
            $content .= '<p>If you’d like to continue using our services, you can re-subscribe anytime from your dashboard.</p>';

            $content .= '<p>Reactivate your subscription visti your admin dashboard.</p>';
            $content .= '<p>If you have any questions or need assistance, our support team is always happy to help.</p>';
            $content .= '<p>Thank you for being with us.</p>';

            $content .= '<p>Warm regards, <br/>Gas Safety Engineer Team</p>';

            GCEMailerJob::dispatch($this->configuration, [$userPackage->user->email], new GCESendMail($subject, $content, []), ['limon@lcc.ac.uk']);
            Log::info('Subscription successfully deleted');
            
        } catch (\Exception $e) {
            Log::error('Subscription deleted webhook error: ' . $e->getMessage());
        }
    }
    
    private function handleSubscriptionCreated($subscriptionData)
    {
        try {
            $subscription_id = $subscriptionData['id'];
            $customer_id = $subscriptionData['customer'];
            $itemData = $subscriptionData['items']['data'][0] ?? [];
            $pricingPlanId = (isset($itemData['plan']['id']) && !empty($itemData['plan']['id']) ? $itemData['plan']['id'] : null);
            $package = ($pricingPlanId ? PricingPackage::where('stripe_plan', $pricingPlanId)->first() : []);

            // Find subscription by customer ID
            if(empty($package) && !empty($customer_id)):
                $subscription = UserPricingPackage::with('package')->where('stripe_customer_id', $customer_id)->whereHas('package', function($q) use($pricingPlanId){
                    $q->where('stripe_plan', $pricingPlanId);
                })->orderBy('id', 'DESC')->first();
                $package = $subscription ?? [];
            elseif(empty($package) && !empty($subscription_id)):
                $subscription = UserPricingPackage::with('package')->where('stripe_subscription_id', $subscription_id)>whereHas('package', function($q) use($pricingPlanId){
                    $q->where('stripe_plan', $pricingPlanId);
                })->orderBy('id', 'DESC')->first();
                $package = $subscription ?? [];
            endif;
                
            if ($package) {
                // Get subscription from Stripe for dates
                $stripeSubscription = $this->stripe->subscriptions->retrieve($subscription_id);
                $stripeCustomer = $this->stripe->customers->retrieve($customer_id);
                

                $subject = 'We\'ve received your subscription request';

                $content = '<p>Hi '.$stripeCustomer->name.',</p>';
                $content .= '<p>Thanks for choosing the '.$package->title.' plan! We’ve successfully received your subscription request.</p>';
                $content .= '<p><strong>What\'s happening now?</strong><br/>Your subscription has been created, and we\'re currently waiting for payment confirmation from your bank or card provider. Once the payment is confirmed:</p>';
                $content .= '<p>';
                    $content .= 'Your subscription will be activated<br/>';
                    $content .= 'Full access will be granted automatically<br/>';
                    $content .= 'You\'ll receive a confirmation email right away<br/>';
                $content .= '</p>';
                $content .= '<p><strong>Subscription Summary:</strong></p>';
                $content .= '<p>';
                    $content .= '<strong>Plan:</strong> '.$package->title.'<br/>';
                    $content .= '<strong>Billing Cycle:</strong>'.$package->period.'<br/>';
                    $content .= '<strong>Amount Due:</strong> '.Number::currency($package->price, 'GBP').'<br/>';
                $content .= '</p>';
                $content .= '<p>No action is required from you at the moment — just sit tight. This usually takes only a few moments.</p>';
                $content .= '<p>If you have any questions or notice an issue with payment, feel free to reply to this email or contact our support team.</p>';
                $content .= '<p>Thanks for your patience, and welcome to - Gas Safety Engineer</p>';

                GCEMailerJob::dispatch($this->configuration, [$stripeCustomer->email], new GCESendMail($subject, $content, []), ['limon@lcc.ac.uk']);
                
                Log::info('Subscription create webhook succeeded', [
                    'subscription_id' => $subscription->id,
                    'customer_id' => $customer_id
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Subscription create webhook error: ' . $e->getMessage(), [
                'customer_id' => $customer_id
            ]);
        }
    }
}