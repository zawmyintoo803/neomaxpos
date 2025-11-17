<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleReportController extends Controller
{
    /**
     * Display the sales report.
     */
    public function index(Request $request)
    {
        // ✅ 1. Date range
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // ✅ 2. Base query with relationships
        $salesQuery = Sale::with(['customer', 'paymentMethod', 'user'])
            ->whereBetween(DB::raw('DATE(sale_date)'), [$startDate, $endDate]);

        // ✅ 3. Sales data
        $sales = $salesQuery->orderBy('sale_date', 'desc')->get();

        // ✅ 4. Summary totals
        $summary = (object) [
            'count'       => $sales->count(),
            'subtotal'    => $sales->sum('subtotal'),
            'tax'         => $sales->sum('tax'),
            'discount'    => $sales->sum('discount'),
            'grand_total' => $sales->sum('grand_total'),
        ];

        // ✅ 5. Daily sales totals
        $dailySales = Sale::selectRaw('DATE(sale_date) as date, SUM(grand_total) as total')
            ->whereBetween(DB::raw('DATE(sale_date)'), [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(sale_date)'))
            ->orderBy('date', 'asc')
            ->get();

        // ✅ 6. Monthly sales totals
        $monthlySales = Sale::selectRaw('DATE_FORMAT(sale_date, "%Y-%m") as month, SUM(grand_total) as total')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->limit(12)
            ->get();

        // ✅ 7. Top 5 best-selling products
        $topProducts = SaleItem::select('product_id', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // ✅ 8. Top 5 customers by total purchase
        $topCustomers = Sale::select(
                DB::raw('COALESCE(customers.name, "Walk-in Customer") as customer_name'),
                DB::raw('SUM(grand_total) as total_amount')
            )
            ->leftJoin('customers', 'sales.customer_id', '=', 'customers.id')
            ->whereBetween(DB::raw('DATE(sale_date)'), [$startDate, $endDate])
            ->groupBy('customers.name')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();

        return view('caisher.reports.sales', compact(
            'sales',
            'summary',
            'dailySales',
            'monthlySales',
            'topProducts',
            'topCustomers',
            'startDate',
            'endDate'
        ));
    }
}
