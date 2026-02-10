<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Transaction;
use App\Models\StudentObligation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total active students
        $totalStudents = Student::where('status', 'active')->count();

        // This month's income
        $monthIncome = Transaction::whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->where('status', 'completed')
            ->where('transaction_type', 'income')
            ->sum('total_amount');

        // Outstanding arrears (unpaid obligations)
        $totalArrears = StudentObligation::where('is_paid', false)->sum('amount');

        // Today's transaction count
        $todayTransactionCount = Transaction::whereDate('transaction_date', today())
            ->where('status', 'completed')
            ->count();

        // Today's income
        $todayIncome = Transaction::whereDate('transaction_date', today())
            ->where('status', 'completed')
            ->where('transaction_type', 'income')
            ->sum('total_amount');

        // Recent transactions (last 5)
        $recentTransactions = Transaction::with(['student.schoolClass'])
            ->where('status', 'completed')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalStudents',
            'monthIncome',
            'totalArrears',
            'todayTransactionCount',
            'todayIncome',
            'recentTransactions'
        ));
    }
}
