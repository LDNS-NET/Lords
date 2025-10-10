<?php

namespace App\Http\Controllers;

use App\Models\SmsLog;
use App\Models\Renter;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

class SmsController extends Controller
{
    public function index()
    {
        $smsLogs = SmsLog::latest()->paginate(10);

        return Inertia::render('Sms/Index', [
            'smsLogs' => $smsLogs,
        ]);
    }

    public function create()
    {
        $renters = Renter::all();

        return Inertia::render('Sms/Create', [
            'renters' => $renters,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipients' => 'required|array',
            'recipients.*' => 'exists:renters,id',
            'message' => 'required|string|max:500',
        ]);

        $renters = Renter::whereIn('id', $validated['recipients'])->get();

        if ($renters->isEmpty()) {
            return redirect()->back()->withErrors(['recipients' => 'No valid renters selected.']);
        }

        $logIds = [];
        $phoneNumbers = [];

        foreach ($renters as $renter) {
            $smsLog = SmsLog::create([
                'recipient_name' => $renter->full_name,
                'phone_number' => $renter->phone_number,
                'message' => $validated['message'],
                'status' => 'pending',
            ]);
            $logIds[] = $smsLog->id;
            $phoneNumbers[] = preg_replace('/^0/', '254', trim($renter->phone_number));
        }

        $phoneNumbersString = implode(',', $phoneNumbers);

        $this->sendSms($logIds, $phoneNumbersString, $validated['message']);

        return redirect()->route('sms.index')
            ->with('success', 'SMS batch is being processed.');
    }

    private function sendSms(array $logIds, string $phoneNumbers, string $message)
    {
        try {
            $apiKey = env('TALKSASA_API_KEY');
            $senderId = env('TALKSASA_SENDER_ID');

            if (!$apiKey || !$senderId) {
                SmsLog::whereIn('id', $logIds)->update([
                    'status' => 'failed',
                    'error_message' => 'Missing TalkSasa API credentials'
                ]);
                return;
            }

            // IMPORTANT: The withoutVerifying() method is used here for local development to bypass SSL certificate issues.
            // This is INSECURE and MUST be removed in a production environment.
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://bulksms.talksasa.com/api/v3/sms/send', [
                'recipient' => $phoneNumbers,
                'sender_id' => $senderId,
                'type' => 'plain',
                'message' => $message,
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['status']) && $data['status'] === 'success') {
                SmsLog::whereIn('id', $logIds)->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            } else {
                SmsLog::whereIn('id', $logIds)->update([
                    'status' => 'failed',
                    'error_message' => $data['message'] ?? $response->body(),
                ]);
            }

        } catch (\Exception $e) {
            SmsLog::whereIn('id', $logIds)->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }

}
