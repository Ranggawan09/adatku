<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\PakaianAdat;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // Prepare the base query to select pakaian-adat
        $query = PakaianAdat::query();

        // Check if the 'nama' input is provided and add the filter to the query
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        // Check if the 'jenis' input is provided and add the filter to the query
        if ($request->filled('jenis')) {
            $query->where('jenis', 'like', '%' . $request->jenis . '%');
        }

        // Check if the 'min_price' input is provided and add the filter to the query
        if ($request->filled('min_price')) {
            $query->where('price_per_day', '>=', $request->min_price);
        }

        // Check if the 'max_price' input is provided and add the filter to the query
        if ($request->filled('max_price')) {
            $query->where('price_per_day', '<=', $request->max_price);
        }

        // Add the 'status' filter to only show available pakaian-adat
        $query->where('status', '=', 'Tersedia');

        // Execute the query and paginate the results
        $pakaianAdats = $query->paginate(12);

        // Include any additional query parameters in the pagination links
        $pakaianAdats->appends($request->except('page'));


        return view('pakaian-adat.searched', compact('pakaianAdats'));
    }
}
