<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function create()
    {
        return view('monitoring.form-identitas');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bayi' => 'required',
            'nama_ibu' => 'nullable',
            'tanggal_lahir' => 'nullable|date',
            'umur_kehamilan' => 'nullable',
            'bb_lahir' => 'nullable',
            'diagnosa' => 'nullable',
            'dokter' => 'nullable',
            'ruangan' => 'nullable',
            'register' => 'nullable',
        ]);

        // nanti diarahkan ke pengisian parameter jam-jamnya
        // saat ini cukup return data dulu
        return back()->with('success', 'Data identitas berhasil disimpan (contoh).');
    }
}
