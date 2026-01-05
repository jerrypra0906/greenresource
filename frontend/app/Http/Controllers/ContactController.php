<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return redirect()->route('contact')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Store inquiry in database
            $inquiry = Inquiry::create([
                'name' => $request->name,
                'company' => $request->company,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'status' => 'new',
                'submitted_at' => now(),
            ]);

            // Get email recipients from site settings
            $recipients = SiteSetting::get('contact_email_recipients', config('mail.from.address'));
            $cc = SiteSetting::get('contact_email_cc', '');
            $bcc = SiteSetting::get('contact_email_bcc', '');

            // Send email notification
            try {
                $emailData = [
                    'inquiry' => $inquiry,
                ];

                $recipientEmails = is_array($recipients) ? $recipients : explode(',', $recipients);
                
                Mail::send('emails.inquiry-notification', $emailData, function ($message) use ($recipientEmails, $cc, $bcc, $inquiry) {
                    $message->to($recipientEmails)
                        ->subject('New Inquiry: ' . $inquiry->subject);
                    
                    if ($cc) {
                        $ccEmails = is_array($cc) ? $cc : explode(',', $cc);
                        $message->cc($ccEmails);
                    }
                    
                    if ($bcc) {
                        $bccEmails = is_array($bcc) ? $bcc : explode(',', $bcc);
                        $message->bcc($bccEmails);
                    }
                });
            } catch (\Exception $e) {
                // Log email error but don't fail the inquiry submission
                Log::error('Failed to send inquiry email: ' . $e->getMessage());
            }

            // Log inquiry for audit trail
            Log::info('New inquiry submitted', [
                'inquiry_id' => $inquiry->id,
                'email' => $inquiry->email,
                'subject' => $inquiry->subject,
            ]);

            return redirect()->route('contact')
                ->with('success', 'Thank you for your inquiry. We will get back to you soon.');
        } catch (\Exception $e) {
            Log::error('Failed to save inquiry: ' . $e->getMessage());
            
            return redirect()->route('contact')
                ->with('error', 'Sorry, there was an error submitting your inquiry. Please try again later.')
                ->withInput();
        }
    }
}

