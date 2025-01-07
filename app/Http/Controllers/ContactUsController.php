<?php

namespace App\Http\Controllers;
use App\Models\ContactUs;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    //

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        $contact = ContactUs::create([
            'name' => $validated['message'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'message' => $validated['message'],
        ]);

        $data = ['name'=> $validated['message'], 'message'=> $validated['message']];
        $error = "";
        try {
            Mail::to($validated['name'])
                ->bcc('contact@partnershipforwellbeing.org')
                ->send(new ContactMail($data));
        } catch (\Exception $e) {
            $error = $e;
            return response()->json([
                'error' => $error,
            ], 422);
        }
        return response()->json([
            'message' => 'Thank you for reaching out! We will get back to you soon.',
            'data' => $contact,
        ], 201);
    }

}
