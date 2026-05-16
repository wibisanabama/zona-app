<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function salesDaily(Request $request)
    {
        $month = $request->input('month', date('Y-m'));
        $startDate = Carbon::parse($month.'-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // Daily Income from Payments
        $dailyIncome = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, sum(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Daily Rentals
        $dailyRentals = Rental::whereBetween('rental_date', [$startDate, $endDate])
            ->selectRaw('rental_date as date, count(*) as total_transactions, sum(total_amount) as total_value')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Prepare data for view (filling missing days)
        $reportData = [];
        $totalIncomeMonth = 0;
        $totalTransactionsMonth = 0;
        $totalValueMonth = 0;

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateString = $date->format('Y-m-d');
            $income = $dailyIncome->has($dateString) ? $dailyIncome[$dateString]->total : 0;
            $rentalsCount = $dailyRentals->has($dateString) ? $dailyRentals[$dateString]->total_transactions : 0;
            $rentalsValue = $dailyRentals->has($dateString) ? $dailyRentals[$dateString]->total_value : 0;

            $reportData[] = [
                'date' => $date->copy(),
                'income' => $income,
                'transactions' => $rentalsCount,
                'value' => $rentalsValue,
            ];

            $totalIncomeMonth += $income;
            $totalTransactionsMonth += $rentalsCount;
            $totalValueMonth += $rentalsValue;
        }

        return view('reports.sales-daily', compact(
            'month',
            'reportData',
            'totalIncomeMonth',
            'totalTransactionsMonth',
            'totalValueMonth'
        ));
    }

    public function exportSalesDailyCsv(Request $request)
    {
        $month = $request->input('month', date('Y-m'));
        $startDate = Carbon::parse($month.'-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $dailyIncome = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, sum(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $dailyRentals = Rental::whereBetween('rental_date', [$startDate, $endDate])
            ->selectRaw('rental_date as date, count(*) as total_transactions, sum(total_amount) as total_value')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $csv = "Tanggal,Penerimaan Uang (Rp),Nilai Transaksi Sewa (Rp),Jml Transaksi\n";

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateString = $date->format('Y-m-d');
            $income = $dailyIncome->has($dateString) ? $dailyIncome[$dateString]->total : 0;
            $rentalsCount = $dailyRentals->has($dateString) ? $dailyRentals[$dateString]->total_transactions : 0;
            $rentalsValue = $dailyRentals->has($dateString) ? $dailyRentals[$dateString]->total_value : 0;

            $csv .= "{$dateString},{$income},{$rentalsValue},{$rentalsCount}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="sales-daily-'.$month.'.csv"');
    }

    public function salesMonthly(Request $request)
    {
        $year = $request->input('year', date('Y'));
        
        $reportData = $this->getSalesMonthlyData($year);

        $totalIncomeYear = collect($reportData)->sum('income');
        $totalTransactionsYear = collect($reportData)->sum('transactions');
        $totalValueYear = collect($reportData)->sum('value');

        return view('reports.sales-monthly', compact(
            'year',
            'reportData',
            'totalIncomeYear',
            'totalTransactionsYear',
            'totalValueYear'
        ));
    }

    private function getSalesMonthlyData($year)
    {
        $startDate = Carbon::create($year, 1, 1)->startOfYear();
        $endDate = $startDate->copy()->endOfYear();

        // Using standard collection grouping for better DB compatibility instead of MONTH()
        $monthlyIncome = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(function($payment) {
                return (int)Carbon::parse($payment->created_at)->format('m');
            })
            ->map(function ($group) {
                return $group->sum('amount');
            });

        $monthlyRentals = Rental::whereBetween('rental_date', [$startDate, $endDate])
            ->get()
            ->groupBy(function($rental) {
                return (int)Carbon::parse($rental->rental_date)->format('m');
            })
            ->map(function ($group) {
                return [
                    'total_transactions' => $group->count(),
                    'total_value' => $group->sum('total_amount'),
                ];
            });

        $reportData = [];
        for ($m = 1; $m <= 12; $m++) {
            $income = $monthlyIncome->has($m) ? $monthlyIncome[$m] : 0;
            $rentalsCount = $monthlyRentals->has($m) ? $monthlyRentals[$m]['total_transactions'] : 0;
            $rentalsValue = $monthlyRentals->has($m) ? $monthlyRentals[$m]['total_value'] : 0;

            $reportData[] = [
                'month' => $m,
                'month_name' => Carbon::create()->month($m)->translatedFormat('F'),
                'income' => $income,
                'transactions' => $rentalsCount,
                'value' => $rentalsValue,
            ];
        }

        return $reportData;
    }

    public function exportSalesMonthlyCsv(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $reportData = $this->getSalesMonthlyData($year);

        $csv = "Bulan,Penerimaan Uang (Rp),Nilai Transaksi Sewa (Rp),Jml Transaksi\n";
        foreach ($reportData as $row) {
            $csv .= "{$row['month_name']},{$row['income']},{$row['value']},{$row['transactions']}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="sales-monthly-'.$year.'.csv"');
    }

    public function itemUtilization(Request $request)
    {
        $month = $request->input('month', date('Y-m'));
        $startDate = Carbon::parse($month.'-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $reportData = $this->getItemUtilizationData($startDate, $endDate);

        $totalQuantity = $reportData->sum('total_quantity');
        $totalRevenue = $reportData->sum('total_revenue');

        return view('reports.item-utilization', compact('month', 'reportData', 'totalQuantity', 'totalRevenue'));
    }

    private function getItemUtilizationData($startDate, $endDate)
    {
        return DB::table('rental_items')
            ->join('rentals', 'rental_items.rental_id', '=', 'rentals.id')
            ->join('items', 'rental_items.item_id', '=', 'items.id')
            ->whereBetween('rentals.rental_date', [$startDate, $endDate])
            ->select(
                'items.sku',
                'items.name',
                DB::raw('SUM(rental_items.quantity) as total_quantity'),
                DB::raw('SUM(rental_items.subtotal) as total_revenue')
            )
            ->groupBy('items.id', 'items.sku', 'items.name')
            ->orderBy('total_quantity', 'desc')
            ->get();
    }

    public function exportItemUtilizationCsv(Request $request)
    {
        $month = $request->input('month', date('Y-m'));
        $startDate = Carbon::parse($month.'-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $reportData = $this->getItemUtilizationData($startDate, $endDate);

        $csv = "SKU,Nama Barang,Total Disewa,Total Pendapatan (Rp)\n";
        foreach ($reportData as $row) {
            $csv .= "{$row->sku},\"{$row->name}\",{$row->total_quantity},{$row->total_revenue}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="item-utilization-'.$month.'.csv"');
    }

    public function overdueRentals()
    {
        $today = Carbon::today();
        $overdueRentals = Rental::with('customer')
            ->where('status', 'aktif')
            ->where('due_date', '<', $today)
            ->orderBy('due_date')
            ->get();

        return view('reports.overdue', compact('overdueRentals', 'today'));
    }

    public function exportOverdueCsv()
    {
        $today = Carbon::today();
        $overdueRentals = Rental::with('customer')
            ->where('status', 'aktif')
            ->where('due_date', '<', $today)
            ->orderBy('due_date')
            ->get();

        $csv = "No. Invoice,Pelanggan,Tanggal Sewa,Tenggat Waktu,Keterlambatan (Hari),Total Tagihan (Rp)\n";
        foreach ($overdueRentals as $rental) {
            $daysLate = (int) Carbon::parse($rental->due_date)->diffInDays($today, true);
            $csv .= "{$rental->invoice_number},\"{$rental->customer->name}\",{$rental->rental_date},{$rental->due_date},{$daysLate},{$rental->total_amount}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="overdue-rentals-'.$today->format('Y-m-d').'.csv"');
    }
}
