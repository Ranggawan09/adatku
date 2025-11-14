<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PakaianAdat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Storage;


class PakaianAdatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = PakaianAdat::with('variants')->latest();

        // Handle search
        if (request()->has('search') && request('search') != '') {
            $searchTerm = request('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', "%{$searchTerm}%")->orWhere('jenis', 'like', "%{$searchTerm}%");
            });
        }
        $pakaianAdats = $query->paginate(7);
        return view('admin.pakaian-adat.index', compact('pakaianAdats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pakaian-adat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'nama' => 'required',
            'jenis' => ['required', Rule::in(['Pria', 'Wanita', 'Anak Laki-Laki', 'Anak Perempuan'])],
            'asal' => 'required',
            'price_per_day' => 'required',
            'status' => ['required', Rule::in(['Tersedia', 'Tidak Tersedia'])],
            'reduce' => 'required',
            'deskripsi' => 'nullable|string',
            'warna' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            // Validasi untuk varian dinamis
            'variants' => 'required|array|min:1',
            'variants.*.size' => 'required|string',
            'variants.*.quantity' => 'required|integer|min:0',
        ]);

        $pakaianAdat = new PakaianAdat;
        $pakaianAdat->nama = $request->nama;
        $pakaianAdat->jenis = $request->jenis;
        $pakaianAdat->asal = $request->asal;
        $pakaianAdat->deskripsi = $request->deskripsi;
        $pakaianAdat->warna = $request->warna;
        $pakaianAdat->price_per_day = $request->price_per_day;
        $pakaianAdat->status = $request->status;
        $pakaianAdat->reduce = $request->reduce;

        if ($request->hasFile('image')) {
            $imageName = $request->nama . '-' . $request->jenis . '-' . $request->asal . '-' . Str::random(10) . '.' . $request->file('image')->extension();
            // Simpan path relatif terhadap disk 'public'
            $path = $request->file('image')->storeAs('pakaian-adat', $imageName, 'public');
            $pakaianAdat->image = $path;
        }
        $pakaianAdat->save();

        // Simpan varian
        foreach ($request->variants as $variantData) {
            $pakaianAdat->variants()->create($variantData);
        }

        return redirect()->route('admin.pakaian-adat.index')->with('success', 'Pakaian Adat berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PakaianAdat $pakaianAdat)
    {
        $pakaianAdat = PakaianAdat::findOrFail($pakaianAdat->id);
        return view('admin.pakaian-adat.edit', compact('pakaianAdat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PakaianAdat $pakaianAdat)
    {
        $request->validate([
            'nama' => 'required',
            'jenis' => ['required', Rule::in(['Pria', 'Wanita', 'Anak Laki-Laki', 'Anak Perempuan'])],
            'asal' => 'required',
            'price_per_day' => 'required',
            'status' => ['required', Rule::in(['Tersedia', 'Tidak Tersedia'])],
            'reduce' => 'required',
            'deskripsi' => 'nullable|string',
            'warna' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            // Validasi untuk varian
            'variants' => 'required|array|min:1',
            'variants.*.id' => 'nullable|integer|exists:pakaian_variants,id',
            'variants.*.size' => 'required|string',
            'variants.*.quantity' => 'required|integer|min:0',
            'deleted_variants' => 'nullable|array',
            'deleted_variants.*' => 'integer|exists:pakaian_variants,id',
        ]);

        $pakaianAdat->nama = $request->nama;
        $pakaianAdat->jenis = $request->jenis;
        $pakaianAdat->asal = $request->asal;
        $pakaianAdat->deskripsi = $request->deskripsi;
        $pakaianAdat->warna = $request->warna;
        $pakaianAdat->price_per_day = $request->price_per_day;
        $pakaianAdat->status = $request->status;
        $pakaianAdat->reduce = $request->reduce;

        if ($request->hasFile('image')) {

            // Hapus gambar lama jika ada
            if ($pakaianAdat->image && Storage::disk('public')->exists($pakaianAdat->image)) {
                Storage::disk('public')->delete($pakaianAdat->image);
            }

            $imageName = $request->nama . '-' . $request->jenis . '-' . $request->asal . '-' . Str::random(10) . '.' . $request->file('image')->extension();
            $path = $request->file('image')->storeAs('pakaian-adat', $imageName, 'public');
            $pakaianAdat->image = $path;
        }
        $pakaianAdat->save();

        // Handle deleted variants
        if ($request->has('deleted_variants')) {
            $pakaianAdat->variants()->whereIn('id', $request->deleted_variants)->delete();
        }

        // Update or create variants
        foreach ($request->variants as $variantData) {
            $pakaianAdat->variants()->updateOrCreate(
                ['id' => $variantData['id'] ?? null], // Condition to find the variant
                [
                    'size' => $variantData['size'],
                    'quantity' => $variantData['quantity']
                ] // Data to update or create
            );
        }

        return redirect()->route('admin.pakaian-adat.index')->with('success', 'Pakaian Adat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PakaianAdat $pakaianAdat)
    {
        $pakaianAdat = PakaianAdat::findOrFail($pakaianAdat->id);
        
        // Cek apakah ada reservasi aktif untuk pakaian adat ini
        if ($pakaianAdat->reservations()->whereIn('status', ['Pending', 'Disewa'])->exists()) {
            return redirect()->route('admin.pakaian-adat.index')->with('error', 'Tidak dapat menghapus Pakaian Adat yang memiliki reservasi aktif.');
        }
        
        // Varian akan terhapus otomatis karena onDelete('cascade') pada migrasi.
        
        // Hapus gambar dari storage
        if ($pakaianAdat->image && Storage::disk('public')->exists($pakaianAdat->image)) {
            Storage::disk('public')->delete($pakaianAdat->image);
        }
        
        $pakaianAdat->delete();

        return redirect()->route('admin.pakaian-adat.index')->with('success', 'Pakaian Adat berhasil dihapus.');
    }
}
