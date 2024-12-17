<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Subscriber;


class SubscriberController extends Controller
{
    
    public function subscribe(Request $request)
    {

        $rules =[
            'email' => 'required|email|unique:subscribers,email',
        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            $messages = $validator->getMessageBag();    
            return response()->json([
                'error' => $messages->first(),
            ], 422);
        }

         Subscriber::create([
            'email' => $request->email,
            'unsubscribed' => false, 
        ]);

        return response()->json([
            'message' => 'Subscription Successful!',
            'description' => 'Thank you for subscribing to our newsletter. You’ll now receive timely updates, insights, and news to stay informed and ahead. We’re excited to have you on board!',
        ], 201);
        
    }
    
}
