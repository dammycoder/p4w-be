<?php

namespace App\Http\Controllers;
use App\Mail\VolunteerMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\Volunteer;


use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    //
    public function submit_volunteer_form(Request $request)
    {
        $rules = [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'age' => 'required|integer|min:12|max:100',
            'gender' => 'required|in:Male,Female,Non-binary,Prefer not to say',
            'address' => 'required|string|max:255',
            'email' => 'required|email|unique:volunteers,email',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'howDidYouHear' => 'required|string|max:255',
            'awareOfPaymentPolicy' => 'required|boolean',
            'instagramHandle' => 'nullable|string|max:255',
            'backupPhoneNumber' => 'required|string|max:20',
            'termsAndConditions' => 'required|boolean',
            'availableForOutreach' => 'required|boolean',
            'skills' => 'required|string',
            'availability' => 'required|in:Weekdays,Weekends,Both',
            'motivation' => 'required|string',
            'hopes' => 'required|string',
        ];


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();    
            return response()->json([
                'error' => $messages->first(),
            ], 422);
        }

    
        Volunteer::create([
            'first_name' => $request->input('firstName'),
            'last_name' => $request->input('lastName'),
            'age' => $request->input('age'),
            'gender' => $request->input('gender'),
            'address' => $request->input('address'),
            'email' => $request->input('email'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'how_did_you_hear' => $request->input('howDidYouHear'),
            'aware_of_payment_policy' => $request->input('awareOfPaymentPolicy'),
            'instagram_handle' => $request->input('instagramHandle'),
            'backup_phone_number' => $request->input('backupPhoneNumber'),
            'terms_and_conditions' => $request->input('termsAndConditions'),
            'available_for_outreach' => $request->input('availableForOutreach'),
            'skills' => $request->input('skills'),
            'availability' => $request->input('availability'),
            'motivation' => $request->input('motivation'),
            'hopes' => $request->input('hopes'),
        ]);

        $receiptients = [$request->email];
        $data = ['name'=> $request->firstName];
        $error = "";
        try {
            Mail::to( $receiptients)
                ->bcc('contact@partnershipforwellbeing.org')
                ->send(new VolunteerMail($data));
        } catch (\Exception $e) {
            $error = $e;
            return response()->json([
                'error' => $error,
            ], 422);
        }

        return response()->json([
            'message' => 'Request Successful!',
            'description' => 'Please watch out for a confirmation email within the next 24 hours. Partnership for Wellbeing reserves the right to decline your request to volunteer.'
        ], 200);
    }
    
}
