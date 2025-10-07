<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Renter;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('renter');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(10);

        return Inertia::render('Payments/Index', [
            'payments' => $payments,
            'filters' => $request->only('status'),
        ]);
    }

    public function create()
    {
        $renters = Renter::all();

        return Inertia::render('Payments/Create', [
            'renters' => $renters,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'renter_id' => 'required|exists:renters,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $renter = Renter::findOrFail($validated['renter_id']);

        $payment = Payment::create([
            'renter_id' => $renter->id,
            'renter_name' => $renter->full_name,
            'amount' => $validated['amount'],
            'reference' => 'REF-' . strtoupper(Str::random(10)),
            'status' => 'pending',
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Payment record created successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load('renter');

        return Inertia::render('Payments/Show', [
            'payment' => $payment,
        ]);
    }

    public function initiate(Request $request)
    {
        $validated = $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'email' => 'required|email',
        ]);

        $payment = Payment::findOrFail($validated['payment_id']);

        // Initialize Paystack payment
        $paystackUrl = $this->initializePaystackPayment($payment, $validated['email']);

        return response()->json([
            'authorization_url' => $paystackUrl,
        ]);
    }

    public function verify(Request $request)
    {
        $reference = $request->query('reference');

        if (!$reference) {
            return redirect()->route('payments.index')
                ->with('error', 'Invalid payment reference.');
        }

        $payment = Payment::where('reference', $reference)->first();

        if (!$payment) {
            return redirect()->route('payments.index')
                ->with('error', 'Payment not found.');
        }

        // Verify payment with Paystack
        $verified = $this->verifyPaystackPayment($reference);

        if ($verified) {
            $payment->update([
                'status' => 'success',
                'paid_at' => now(),
            ]);

            return redirect()->route('payments.show', $payment)
                ->with('success', 'Payment verified successfully.');
        }

        return redirect()->route('payments.show', $payment)
            ->with('error', 'Payment verification failed.');
    }

    public function webhook(Request $request)
    {
        $signature = $request->header('x-paystack-signature');
        $body = $request->getContent();

        $secretKey = env('PAYSTACK_SECRET_KEY');

        if ($signature !== hash_hmac('sha512', $body, $secretKey)) {
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $event = $request->all();

        if ($event['event'] === 'charge.success') {
            $reference = $event['data']['reference'];
            $payment = Payment::where('reference', $reference)->first();

            if ($payment) {
                $payment->update([
                    'status' => 'success',
                    'paid_at' => now(),
                ]);
            }
        }

        return response()->json(['message' => 'Webhook processed'], 200);
    }

    private function initializePaystackPayment(Payment $payment, $email)
    {
        $secretKey = env('PAYSTACK_SECRET_KEY');

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $secretKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.paystack.co/transaction/initialize', [
            'email' => $email,
            'amount' => $payment->amount * 100, // Convert to kobo/cents
            'reference' => $payment->reference,
            'callback_url' => route('payments.verify'),
        ]);

        if ($response->successful()) {
            return $response->json()['data']['authorization_url'];
        }

        return null;
    }

    private function verifyPaystackPayment($reference)
    {
        $secretKey = env('PAYSTACK_SECRET_KEY');

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $secretKey,
        ])->get("https://api.paystack.co/transaction/verify/{$reference}");

        if ($response->successful()) {
            $data = $response->json()['data'];
            return $data['status'] === 'success';
        }

        return false;
    }

    public function export(Request $request)
    {
        $payments = Payment::with('renter')->get();

        $filename = 'payments_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($payments) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Renter Name', 'Amount', 'Reference', 'Status', 'Paid At', 'Created At']);

            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->id,
                    $payment->renter_name,
                    $payment->amount,
                    $payment->reference,
                    $payment->status,
                    $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : 'N/A',
                    $payment->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }
}
