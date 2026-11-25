<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesin;

class MesinController extends Controller
{
    public function index()
    {
        $daftar_mesin = Mesin::all();

        $total_mesin = Mesin::count();
        $beroperasi = Mesin::where('status', 'Beroperasi')->count();
        $perawatan = Mesin::where('status', 'Perawatan')->count();
        $rusak = Mesin::where('status', 'Rusak')->count();

        return view('mesin', compact('daftar_mesin', 'total_mesin', 'beroperasi', 'perawatan', 'rusak'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode'      => 'required|string|max:20|unique:mesin,kode',
            'nama'      => 'required|string|max:100',
            'jenis'     => 'required|string|max:50',
            'merek'     => 'required|string|max:50',
            'kapasitas' => 'nullable|integer|min:1',
            'status'    => 'required|string|max:30',
        ], [
            'kode.unique' => 'Kode mesin sudah digunakan. Gunakan kode lain.',
        ]);

        Mesin::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'merek' => $request->merek,
            'kapasitas' => $request->kapasitas,
            'status' => $request->status,
        ]);

        return redirect()->back();
    }

    public function hapus($kode)
    {
        Mesin::where('kode', $kode)->delete();
        
        return redirect()->back();
    }

    public function edit($kode)
    {
        $mesin = Mesin::where('kode', $kode)->first();
        return view('mesin_edit', compact('mesin'));
    }

    public function update(Request $request, $kode)
    {
        $request->validate([
            'nama' => 'required',
            'jenis' => 'required',
            'merek' => 'required',
            'status' => 'required',
        ]);

        Mesin::where('kode', $kode)->update([
            'nama' => $request->nama,
            'jenis' => $request->jenis,
            'merek' => $request->merek,
            'kapasitas' => $request->kapasitas,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.mesin.index');
    }
}
