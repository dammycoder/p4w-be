<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use App\Mail\DonationMail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Log;


use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //
    public function handleWebhook(Request $request){
        if (!$request->hasHeader("x-paystack-signature")) exit("No header present");
        $secret = env("PAYSTACK_SECRET_KEY"); 
        if ($request->header("x-paystack-signature") !== hash_hmac("sha512", $request->getContent(), $secret)) exit("Invalid signatute");
        $event = $request->event; 
        $data = json_decode($request->getContent());

        if ($event === "charge.success") {
            $reference = $data->data->reference;
            $amount = $data->data->amount;
            $email = $data->data->customer->email;

            $customFields = $data->data->metadata->custom_fields ?? [];

            $firstname = "";
            $lastname = "";
            
            foreach ($customFields as $field) {
                if ($field->variable_name === 'first_name') {
                    $firstname = $field->value;
                } elseif ($field->variable_name === 'last_name') {
                    $lastname = $field->value;
                }
            }
            
            Payment::create([
                'reference' => $reference,
                'amount' => $amount,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'status' => 'successful',
            ]);

            $data = ['name'=> $firstname, 'amount' => $amount];
            try {
              Mail::to($email)
                  ->bcc('contact@partnershipforwellbeing.org')
                  ->send(new DonationMail($data));
          } catch (\Exception $e) {
              return response()->json([
                  'error' => 'Payment Successful! Failed to send Mail',
              ], 422);
          }
          }
          return response()->json('', 200);
      }

}
