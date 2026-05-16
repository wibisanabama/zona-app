<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    public function index(Request $request)
    {
        $query = Rental::with(['customer', 'cashier']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', function ($cq) use ($request) {
                      $cq->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'terlambat') {
                $query->where('status', 'aktif')->where('due_date', '<', Carbon::today());
            } else {
                $query->where('status', $request->status);
            }
        }

        $rentals = $query->latest()->paginate(15)->withQueryString();

        return view('rentals.index', compact('rentals'));
    }

    public function show(Rental $rental)
    {
        $rental->load(['customer', 'cashier', 'rentalItems.item', 'payments']);

        return view('rentals.show', compact('rental'));
    }

    public function returnForm(Rental $rental)
    {
        if ($rental->status !== 'aktif') {
            return back()->with('error', 'Hanya sewa aktif yang dapat dikembalikan.');
        }

        $rental->load(['customer', 'rentalItems.item']);

        return view('rentals.return', compact('rental'));
    }

    public function processReturn(Request $request, Rental $rental)
    {
        if ($rental->status !== 'aktif') {
            return back()->with('error', 'Sewa ini tidak dalam status aktif.');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.rental_item_id' => 'required|exists:rental_items,id',
            'items.*.returned_quantity' => 'required|integer|min:0',
            'items.*.condition_on_return' => 'required|in:baik,perlu_perbaikan,rusak',
        ]);

        return DB::transaction(function () use ($request, $rental) {
            $today = Carbon::today();
            $lateFee = 0;

            // Check overdue
            if ($today->gt($rental->due_date)) {
                $lateDays = $today->diffInDays($rental->due_date);
                $lateFee = $rental->subtotal / $rental->days * $lateDays * 0.5; // 50% per late day
            }

            foreach ($request->items as $ri) {
                $rentalItem = $rental->rentalItems()->findOrFail($ri['rental_item_id']);
                $returnQty = min($ri['returned_quantity'], $rentalItem->quantity);

                $rentalItem->update([
                    'returned_quantity' => $returnQty,
                    'condition_on_return' => $ri['condition_on_return'],
                ]);

                // Restore stock
                Item::where('id', $rentalItem->item_id)
                    ->increment('stock_available', $returnQty);
            }

            $rental->update([
                'status' => 'selesai',
                'actual_return_date' => $today,
                'late_fee' => round($lateFee),
            ]);

            return redirect()->route('rentals.show', $rental)
                ->with('success', 'Pengembalian berhasil diproses.' . ($lateFee > 0 ? " Denda keterlambatan: Rp " . number_format($lateFee, 0, ',', '.') : ''));
        });
    }

    public function cancel(Rental $rental)
    {
        if ($rental->status !== 'aktif') {
            return back()->with('error', 'Hanya sewa aktif yang dapat dibatalkan.');
        }

        return DB::transaction(function () use ($rental) {
            // Restore stock
            foreach ($rental->rentalItems as $ri) {
                Item::where('id', $ri->item_id)
                    ->increment('stock_available', $ri->quantity);
            }

            $rental->update(['status' => 'batal']);

            return redirect()->route('rentals.index')
                ->with('success', 'Sewa ' . $rental->code . ' berhasil dibatalkan dan stok dikembalikan.');
        });
    }
}
