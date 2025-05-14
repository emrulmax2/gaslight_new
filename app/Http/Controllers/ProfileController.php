<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileNameRequest;
use App\Jobs\GCEMailerJob;
use App\Mail\GCESendMail;
use App\Models\FileRecord;
use App\Models\Staff;
use App\Models\User;
use App\Models\UserPricingPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Creagia\LaravelSignPad\Signature;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Number;
use Stripe;

class ProfileController extends Controller
{
    
    public function index()
    {
        return view('app.profile.index', [
            'title' => 'Profile - Gas Certificate APP',
            'user' => User::find(auth()->user()->id),
            'method' => $this->paymentMethods(auth()->user()->id)
        ]);
    }

    public function update(ProfileUpdateRequest $request){
        $user = User::find(auth()->user()->id);
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'gas_safe_id_card' => $request->gas_safe_id_card,
            'oil_registration_number' => $request->oil_registration_number,
            'installer_ref_no' => $request->installer_ref_no,
        ];
        
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->input('password'));
        }
        $user->update($updateData);
        return response()->json(['message' => 'Profile updated successfully'], 200);
    }


    public function drawSignatureStore(Request $request)
    {

        $user = User::find(auth()->user()->id);

        $existingSignature = Signature::where('model_type', User::class)
            ->where('model_id', $user->id)
            ->first();

        if($request->input('sign') !== null) {
            if ($existingSignature) {
                $filePath = storage_path('app/public/' . $existingSignature->filename);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $existingSignature->delete();
            }

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

            return response()->json(['message' => 'Signature created successfully'], 201);
        }
        return response()->json(['message' => 'Signature could not be created'], 400);
    }


    public function fileUploadStore(Request $request)
    {
        $user = User::find(auth()->user()->id);
    
        if ($request->input('file_id') !== null) {
            $fileRecord = FileRecord::find($request->input('file_id'));
    
            if ($fileRecord) {
                $newFilePath = 'signatures/' . Str::uuid() . '.' . pathinfo($fileRecord->name, PATHINFO_EXTENSION);
                Storage::disk('public')->move($fileRecord->path, $newFilePath);
    
                $signature = new Signature();
                $signature->model_type = User::class;
                $signature->model_id = $user->id;
                $signature->uuid = Str::uuid();
                $signature->filename = $newFilePath;
                $signature->document_filename = null;
                $signature->certified = false;
                $signature->from_ips = json_encode([request()->ip()]);
                $signature->save();
    
                return response()->json(['message' => 'Signature uploaded successfully'], 201);
            }
        }
        return response()->json(['message' => 'Signature could not be uploaded'], 400);
    }

    public function updateData(Request $request){
        $user_id = $request->id;
        $value = (isset($request->fieldValue) && !empty($request->fieldValue) ? $request->fieldValue : null);
        $field = $request->fieldName;

        if($user_id > 0 && $field != ''):
            $user = User::find($user_id);
            $user->update([$field => $value]);

            return response()->json(['msg' => 'User data successfully updated.'], 200);
        else:
            return response()->json(['msg' => 'Something went wrong. Please try again later or contact with the administrator.'], 304);
        endif;
    }

    public function updatePassword(UpdatePasswordRequest $request){
        $user_id = $request->id;
        if(!empty($request->input('password'))):
            $user = User::find($user_id);
            $user->update(['password' => Hash::make($request->input('password'))]);

            $configuration = [
                'smtp_host' => env('MAIL_HOST', 'smtp.gmail.com'),
                'smtp_port' => env('MAIL_PORT', '587'),
                'smtp_username' => env('MAIL_USERNAME', 'no-reply@lcc.ac.uk'),
                'smtp_password' => env('MAIL_PASSWORD', 'PASSWORD'),
                'smtp_encryption' => env('MAIL_ENCRYPTION', 'tls'),
                
                'from_email'    => env('MAIL_FROM_ADDRESS', 'no-reply@lcc.ac.uk'),
                'from_name'    =>  env('MAIL_FROM_NAME', 'Gas Safe Engineer'),

            ];

            $subject = 'Your Password Has Been Successfully Changed';

            $content = 'Hi '.$user->name.',<br/><br/>';
            $content .= '<p>This is to inform you that the password for your account has been successfully changed.</p>';
            $content .= '<p>
                            If you made this change, no further action is required.<br/>
                            If you did not authorize this change, please reset your password immediately and contact our support team for assistance.
                        </p>';
            $content .= '<p>';
                $content .= 'Date & Time of Change: <strong>'.date('jS F, Y h:i A').'</strong><br/>';
                $content .= 'IP Address: <strong>'.$request->ip().'</strong>';
            $content .= '</p>';
            $content .= '<p>For your security, please do not share your password with anyone. If you have any questions, feel free to reach out to us.</p>';

            $content .= 'Thanks & Regards<br/>';
            $content .= 'Gas Safety Engineer';

            GCEMailerJob::dispatch($configuration, [$user->email], new GCESendMail($subject, $content, []));
            
            return response()->json(['msg' => 'You password successfully updated.'], 200);
        else:
            return response()->json(['msg' => 'Password can not be empty.'], 304);
        endif;
    }

    public function paymentMethods($user_id){
        $userPackage = UserPricingPackage::where('user_id', $user_id)->orderBy('id', 'DESC')->get()->first();
        $paymentData = [];
        if(isset($userPackage->stripe_customer_id) && !empty($userPackage->stripe_customer_id)):
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $customer = $stripe->customers->retrieve($userPackage->stripe_customer_id);
            try{
                $paymentMethod = (isset($customer->invoice_settings->default_payment_method) && !empty($customer->invoice_settings->default_payment_method) ? $customer->invoice_settings->default_payment_method : '');
                if(!empty($paymentMethod)):
                    try{
                        $paymentMethod = $stripe->customers->retrievePaymentMethod(
                            $userPackage->stripe_customer_id,
                            $paymentMethod
                        );
                        $paymentData['brand'] = $paymentMethod->card->brand;
                        $paymentData['display_brand'] = $paymentMethod->card->display_brand;
                        $paymentData['last4'] = $paymentMethod->card->last4;
                        $paymentData['exp_month'] = $paymentMethod->card->exp_month;
                        $paymentData['exp_year'] = $paymentMethod->card->exp_year;

                        $session = $stripe->billingPortal->sessions->create([
                            'customer' => $userPackage->stripe_customer_id,
                            'return_url' => route('profile'),
                        ]);
                        $paymentData['portal_url'] = (isset($session->url) && !empty($session->url) ? $session->url : '');
                        
                    }catch(Exception $e){
                        //return redirect('profile');
                    }
                else:
                    //return redirect('profile');
                endif;
            }catch(Exception $e){
                //return redirect('profile');
            }
        endif;

        return $paymentData;
    }


    public function updatePhoto(UpdateProfileNameRequest $request){
        $user_id = $request->id;
        $user = User::find($user_id);
        $user->update(['name' => $request->name]);

        if($request->hasFile('photo')):
            if(isset($user->photo) && !empty($user->photo) && Storage::disk('public')->exists('public/users/'.$user_id.'/'.$user->photo)):
                Storage::disk('public')->delete('public/users/'.$user_id.'/'.$user->photo);
            endif;

            $photo = $request->file('photo');
            $imageName = 'Avatar_'.$user_id.'_'.time() . '.' . $request->photo->getClientOriginalExtension();
            $path = $photo->storeAs('users/'.$user->id, $imageName, 'public');

            $userUpdate = User::where('id', $user->id)->update([
                'photo' => $imageName
            ]);
        endif;

        return response()->json(['msg' => 'User details successfully updated.'], 200);
    }

}

