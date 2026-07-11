<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
{
    // Total pendapatan
    $totalRevenue = Transaction::whereIn('status', [
        'settlement',
        'success'
    ])->sum('total_price');

    // Tiket terjual
    $ticketsSold = Transaction::whereIn('status', [
        'settlement',
        'success'
    ])->count();

    // Event aktif
    $activeEvents = Event::where('date', '>=', now())
        ->count();

    // Pesanan pending
    $pendingOrders = Transaction::where('status', 'pending')
        ->count();

    // Riwayat transaksi terbaru
    $recentTransactions = Transaction::with('event')
        ->latest()
        ->take(5)
        ->get();

    return view('admin.dashboard', compact(
        'totalRevenue',
        'ticketsSold',
        'activeEvents',
        'pendingOrders',
        'recentTransactions'
    ));
}
}
