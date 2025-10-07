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

        foreach ($renters as $renter) {
            $smsLog = SmsLog::create([
                'recipient_name' => $renter->full_name,
                'phone_number' => $renter->phone_number,
                'message' => $validated['message'],
                'status' => 'pending',
            ]);

            // Send SMS via TalkSasa API
            $this->sendSms($smsLog);
        }

        return redirect()->route('sms.index')
            ->with('success', 'SMS sent successfully.');
    }

    private function sendSms(SmsLog $smsLog)
    {
        try {
            // TalkSasa API integration
            $apiKey = env('TALKSASA_API_KEY');
            $senderId = env('TALKSASA_SENDER_ID');

            if (!$apiKey) {
                $smsLog->update(['status' => 'failed']);
                return;
            }

            $response = Http::post('https://api.talksasa.com/v1/sms/send', [
                'api_key' => $apiKey,
                'sender_id' => $senderId,
                'phone' => $smsLog->phone_number,
                'message' => $smsLog->message,
            ]);

            if ($response->successful()) {
                $smsLog->update([
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            } else {
                $smsLog->update(['status' => 'failed']);
            }
        } catch (\Exception $e) {
            $smsLog->update(['status' => 'failed']);
        }
    }
}
