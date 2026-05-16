<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function store(Request $request, Rental $rental)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|in:tunai,transfer,qris',
            'type' => 'required|in:sewa,deposit,denda,refund',
            'notes' => 'nullable|string|max:500',
        ], [
            'amount.required' => 'Jumlah pembayaran wajib diisi.',
            'amount.min' => 'Jumlah pembayaran minimal Rp 1.',
            'method.required' => 'Metode pembayaran wajib dipilih.',
            'type.required' => 'Jenis pembayaran wajib dipilih.',
        ]);

        $payment = Payment::create([
            'rental_id' => $rental->id,
            'received_by' => Auth::id(),
            'amount' => $request->amount,
            'method' => $request->method,
            'type' => $request->type,
            'notes' => $request->notes,
        ]);

        // Update paid_amount on rental
        $totalPaid = $rental->payments()->sum('amount');
        $rental->update(['paid_amount' => $totalPaid]);

        return redirect()->route('rentals.show', $rental)
            ->with('success', 'Pembayaran Rp ' . number_format($request->amount, 0, ',', '.') . ' berhasil dicatat.');
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
