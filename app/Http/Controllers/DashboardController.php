<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Payment;
use App\Models\Item;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with KPI metrics.
     */
    public function index()
    {
        $today = Carbon::today();

        // 1. Sewa Aktif
        $activeRentalsCount = Rental::where('status', 'aktif')->count();

        // 2. Pendapatan Hari Ini
        $todayIncome = Payment::whereDate('created_at', $today)->sum('amount');

        // 3. Total Barang
        $totalItems = Item::count();

        // 4. Total Pelanggan
        $totalCustomers = Customer::count();

        // 5. Sewa Terlambat (Overdue)
        $overdueRentals = Rental::with('customer')
            ->where('status', 'aktif')
            ->where('due_date', '<', $today)
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // 6. Sewa Aktif Terbaru
        $recentRentals = Rental::with('customer')
            ->where('status', 'aktif')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'activeRentalsCount',
            'todayIncome',
            'totalItems',
            'totalCustomers',
            'overdueRentals',
            'recentRentals'
        ));
    }
}
