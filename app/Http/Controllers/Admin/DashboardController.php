<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PakaianAdat;
use App\Models\Reservation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $PakaianAdatCount = PakaianAdat::where('status', 'Tersedia')->count();
        $userCount = User::where('role', 'client')->count();
        $reservationCount = Reservation::where('status', 'Disewa')->distinct('order_id')->count();

        // Data untuk Chart Reservasi 30 Hari Terakhir
        $reservationData = Reservation::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $reservationLabels = $reservationData->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('d M');
        });
        $reservationValues = $reservationData->pluck('count');

        // Data untuk Chart Pakaian Adat Terpopuler (Top 5)
        $popularPakaian = Reservation::select('pakaian_adat_id', DB::raw('count(*) as total'))
            ->with('pakaianAdat') // Eager load relasi
            ->groupBy('pakaian_adat_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        $popularPakaianLabels = $popularPakaian->map(fn($item) => $item->pakaianAdat->nama);
        $popularPakaianValues = $popularPakaian->pluck('total');

        return view('admin.dashboard', compact(
            'PakaianAdatCount', 'userCount', 'reservationCount',
            'reservationLabels', 'reservationValues',
            'popularPakaianLabels', 'popularPakaianValues'
        ));
    }
}