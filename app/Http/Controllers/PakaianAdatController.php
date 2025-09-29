<?php

namespace App\Http\Controllers;

use App\Models\PakaianAdat;
use Illuminate\Http\Request;

class PakaianAdatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PakaianAdat::query()->where('status', 'Tersedia')->latest();

        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', 'like', '%' . $request->jenis . '%');
        }

        if ($request->filled('asal')) {
            $query->where('asal', 'like', '%' . $request->asal . '%');
        }

        if ($request->filled('warna')) {
            $query->where('warna', 'like', '%' . $request->warna . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('price_per_day', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }

        $pakaianAdats = $query->paginate(12);

        return view('pakaian-adat.index', compact('pakaianAdats'));
    }
}