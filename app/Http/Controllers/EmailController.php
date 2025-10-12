<?php

namespace App\Http\Controllers;

use App\Models\EmailLog;
use App\Models\Renter;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class EmailController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('perPage', 10);
        $emailLogs = EmailLog::latest()->paginate($perPage);

        return Inertia::render('Emails/Index', [
            'emailLogs' => $emailLogs,
            'perPage' => $perPage,
        ]);
    }

    public function create()
    {
        $renters = Renter::all();

        return Inertia::render('Emails/Create', [
            'renters' => $renters,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipients' => 'required|array',
            'recipients.*' => 'exists:renters,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $renters = Renter::whereIn('id', $validated['recipients'])->get();
        $recipientEmails = $renters->pluck('email')->toArray();

        $emailLog = EmailLog::create([
            'subject' => $validated['subject'],
            'recipients' => $recipientEmails,
            'body' => $validated['body'],
            'status' => 'pending',
        ]);

        // Send email via SendGrid
        $this->sendEmail($emailLog);

        return redirect()->route('emails.index')
            ->with('success', 'Email sent successfully.');
    }

    private function sendEmail(EmailLog $emailLog)
    {
        try {
            $apiKey = env('SENDGRID_API_KEY');
            $fromEmail = env('MAIL_FROM_ADDRESS');
            $fromName = env('MAIL_FROM_NAME');

            if (!$apiKey) {
                $emailLog->update(['status' => 'failed']);
                return;
            }

            $personalizations = array_map(function ($email) {
                return ['to' => [['email' => $email]]];
            }, $emailLog->recipients);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.sendgrid.com/v3/mail/send', [
                'personalizations' => $personalizations,
                'from' => [
                    'email' => $fromEmail,
                    'name' => $fromName
                ],
                'subject' => $emailLog->subject,
                'content' => [
                    [
                        'type' => 'text/html',
                        'value' => $emailLog->body,
                    ],
                ],
            ]);

            if ($response->successful() || $response->status() === 202) {
                $emailLog->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            } else {
                $emailLog->update(['status' => 'failed']);
            }
        } catch (\Exception $e) {
            $emailLog->update(['status' => 'failed']);
        }
    }
}
