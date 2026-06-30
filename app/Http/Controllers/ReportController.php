<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();

        return view('reports.index', [
            'dueToday' => Expense::query()
                ->with(['company.group'])
                ->where('status', 'pending')
                ->whereDate('due_date', $today)
                ->orderBy('company_id')
                ->get(),
            'overdue' => Expense::query()
                ->with(['company.group'])
                ->where('status', 'pending')
                ->whereDate('due_date', '<', $today)
                ->orderBy('due_date')
                ->get(),
            'paymentHistory' => Expense::query()
                ->with(['company.group'])
                ->where('status', 'paid')
                ->whereNotNull('paid_at')
                ->orderByDesc('paid_at')
                ->get(),
        ]);
    }
}
