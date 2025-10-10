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

        $phoneNumbers = $renters->map(function ($renter) {
            return preg_replace('/^0/', '254', trim($renter->phone_number));
        })->implode(',');

        $smsLog = SmsLog::create([
            'recipient_name' => 'Bulk SMS',
            'phone_number' => $phoneNumbers,
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        $this->sendSms($smsLog, $phoneNumbers);

        return redirect()->route('sms.index')
            ->with('success', 'SMS batch is being processed.');
    }

    private function sendSms(SmsLog $smsLog, string $phoneNumbers)
    {
        try {
            $apiKey = env('TALKSASA_API_KEY');
            $senderId = env('TALKSASA_SENDER_ID');

            if (!$apiKey || !$senderId) {
                $smsLog->update([
                    'status' => 'failed',
                    'error_message' => 'Missing TalkSasa API credentials'
                ]);
                return;
            }

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post('https://bulksms.talksasa.com/api/sms/v1/sendsms', [
                'api_key' => $apiKey,
                'sender_id' => $senderId,
                'message' => $smsLog->message,
                'phone' => $phoneNumbers,
            ]);

            $data = $response->json();

            if ($response->successful() && isset($data['status']) && $data['status'] === 'success') {
                $smsLog->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            } else {
                $smsLog->update([
                    'status' => 'failed',
                    'error_message' => $data['message'] ?? $response->body(),
                ]);
            }

        } catch (\Exception $e) {
            $smsLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }

}
