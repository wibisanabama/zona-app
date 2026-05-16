<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Rental;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
}
