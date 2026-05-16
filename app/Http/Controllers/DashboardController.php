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
        return view('dashboard.index');
    }
}
