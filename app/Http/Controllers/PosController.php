<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRentalRequest;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Rental;
use App\Models\RentalItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function create()
    {
        $customers = Customer::where('blacklisted', false)->orderBy('name')->get();

        return view('pos.create', compact('customers'));
    }

    public function searchItems(Request $request)
    {
        $query = Item::with('category')->available();

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('sku', 'like', '%'.$request->q.'%');
            });
        }

        $items = $query->orderBy('name')->limit(20)->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'sku' => $item->sku,
                'name' => $item->name,
                'category' => $item->category->name ?? '',
                'daily_rate' => (float) $item->daily_rate,
                'deposit_amount' => (float) $item->deposit_amount,
                'stock_available' => $item->stock_available,
                'formatted_rate' => $item->formatted_daily_rate,
                'photo_url' => $item->photo_url,
            ];
        });

        return response()->json($items);
    }

    public function store(StoreRentalRequest $request)
    {
        $data = $request->validated();

        return DB::transaction(function () use ($data) {
            $days = (int) $data['days'];
            $cartItems = $data['items'];
            $subtotal = 0;
            $totalDeposit = 0;

            // Validate stock and calculate totals
            foreach ($cartItems as &$ci) {
                $item = Item::lockForUpdate()->findOrFail($ci['item_id']);

                if ($item->stock_available < $ci['quantity']) {
                    throw new \Exception("Stok {$item->name} tidak mencukupi. Tersedia: {$item->stock_available}");
                }

                $ci['daily_rate'] = (float) $item->daily_rate;
                $ci['deposit_amount'] = (float) $item->deposit_amount;
                $ci['line_subtotal'] = $ci['daily_rate'] * $ci['quantity'] * $days;
                $ci['line_deposit'] = $ci['deposit_amount'] * $ci['quantity'];

                $subtotal += $ci['line_subtotal'];
                $totalDeposit += $ci['line_deposit'];
            }

            $discount = (float) ($data['discount'] ?? 0);
            $totalAmount = $subtotal - $discount;

            // Create rental
            $rental = Rental::create([
                'customer_id' => $data['customer_id'],
                'cashier_id' => Auth::id(),
                'rental_date' => $data['rental_date'],
                'due_date' => date('Y-m-d', strtotime($data['rental_date']." + {$days} days")),
                'days' => $days,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total_deposit' => $totalDeposit,
                'total_amount' => $totalAmount,
                'status' => 'aktif',
                'notes' => $data['notes'] ?? null,
            ]);

            // Create rental items and decrement stock
            foreach ($cartItems as $ci) {
                RentalItem::create([
                    'rental_id' => $rental->id,
                    'item_id' => $ci['item_id'],
                    'quantity' => $ci['quantity'],
                    'daily_rate' => $ci['daily_rate'],
                    'deposit_amount' => $ci['deposit_amount'],
                    'subtotal' => $ci['line_subtotal'],
                ]);

                Item::where('id', $ci['item_id'])
                    ->decrement('stock_available', $ci['quantity']);
            }

            return redirect()->route('pos.receipt', $rental)
                ->with('success', 'Transaksi sewa berhasil dibuat.');
        });
    }

    public function receipt(Rental $rental)
    {
        $rental->load(['customer', 'cashier', 'rentalItems.item']);

        return view('pos.receipt', compact('rental'));
    }
}
