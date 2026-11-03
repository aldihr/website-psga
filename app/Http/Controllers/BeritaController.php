<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index()
    {
        $berita = Berita::latest()->paginate(10);

        return view('berita.index', compact('berita'));
    }

    public function create()
    {
        return view('berita.create');
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'ringkasan' => 'nullable|string',
            'isi' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'kategori' => 'nullable|string|max:100',
            'status' => 'required|in:draft,terbit,arsip',
        ]);

        $gambar = null;

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')
                ->store('berita', 'public');
        }

        Berita::create([
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul) . '-' . uniqid(),
            'ringkasan' => $request->ringkasan,
            'isi' => $request->isi,
            'gambar' => $gambar,
            'kategori' => $request->kategori,
            'status' => $request->status,
            'tanggal_terbit' => $request->status === 'terbit'
                ? now()
                : null,
        ]);

        return redirect()
            ->route('berita.index')
            ->with('success', 'Berita berhasil disimpan.');
    }

    public function beranda()
    {
        $beritaBeranda = Berita::where('status', 'terbit')
            ->latest('tanggal_terbit')
            ->take(4)
            ->get();

        return view('home', compact('beritaBeranda'));
    }

    
public function show($slug)
{
    $berita = Berita::where('slug', $slug)
        ->where('status', 'terbit')
        ->firstOrFail();

    return view('berita.show', compact('berita'));
}

public function arsip()
{
    $berita = Berita::where('status', 'arsip')
        ->latest('tanggal_terbit')
        ->paginate(10);

    return view('berita.arsip', compact('berita'));
}

public function arsipkan($id)
{
    $berita = Berita::findOrFail($id);

    $berita->update([
        'status' => 'arsip',
    ]);

    return redirect()
        ->route('berita.index')
        ->with('success', 'Berita berhasil diarsipkan.');
}

public function hapus($id)
{
    $berita = Berita::findOrFail($id);

    $berita->delete();

    return redirect()
        ->route('berita.index')
        ->with('success', 'Berita berhasil dihapus.');
}

public function pulihkan($id)
{
    $berita = Berita::findOrFail($id);

    $berita->update([
        'status' => 'terbit',
    ]);

    return redirect()
        ->route('berita.arsip')
        ->with('success', 'Berita berhasil dipulihkan.');
}

}
