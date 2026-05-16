<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Rental;
use App\Http\Requests\StorePaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request, Rental $rental)
    {
        $data = $request->validated();
        $data['rental_id'] = $rental->id;
        $data['cashier_id'] = Auth::id();
        
        if (empty($data['paid_at'])) {
            $data['paid_at'] = now();
        }

        $payment = Payment::create($data);

        // Update paid_amount on rental
        $totalPaid = $rental->payments()->sum('amount');
        $rental->update(['paid_amount' => $totalPaid]);

        return redirect()->route('rentals.show', $rental)
            ->with('success', 'Pembayaran Rp '.number_format($request->amount, 0, ',', '.').' berhasil dicatat.');
    }

    public function destroy(Payment $payment)
    {
        $rental = $payment->rental;
        $payment->delete();

        // Recalculate paid amount
        $totalPaid = $rental->payments()->sum('amount');
        $rental->update(['paid_amount' => $totalPaid]);

        return redirect()->route('rentals.show', $rental)
            ->with('success', 'Pembayaran berhasil dihapus.');
    }
}
