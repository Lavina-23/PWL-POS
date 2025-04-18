<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\KategoriModel; // Import the KategoriModel class

class KategoriController extends Controller
{
    // Untuk menampilkan halaman tabel kategor
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori']
        ];

        $page = (object) [
            'title' => 'Daftar kategori'
        ];

        $activeMenu = 'kategori'; // Set menu yang sedang aktif

        // Ambil semua kategori untuk dropdown filter
        $kategoris = KategoriModel::select('kategori_nama')->distinct()->get();

        return view('kategori.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategoris' => $kategoris
        ]);
    }

    // Untuk menyediakan data kategori dalam format JSON untuk DataTables
    public function list(Request $request)
    {
        $kategori = KategoriModel::query();

        // Filter berdasarkan kategori_nama jika ada
        if ($request->has('kategori_nama') && $request->kategori_nama) {
            $kategori->where('kategori_nama', 'like', '%' . $request->kategori_nama . '%');
        }

        return DataTables::of($kategori)
            ->addIndexColumn() // Tambahkan nomor urut
            ->addColumn('aksi', function ($kategori) {  // menambahkan kolom aksi
                $btn  = '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn;
            })
            ->rawColumns(['aksi']) // Pastikan kolom aksi dirender sebagai HTML
            ->make(true);
    }

    // Untuk menampilkan form tambah kategori
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Kategori',
            'list' => ['Home', 'Kategori', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah kategori baru'
        ];

        $activeMenu = 'kategori'; // Set menu yang sedang aktif

        return view('kategori.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    // Untuk menyimpan data kategori baru
    public function store(Request $request)
    {
        $request->validate([
            'kategori_nama' => 'required|string|max:100', // Validasi nama kategori
        ]);

        // Generate kode kategori otomatis
        $lastKategori = KategoriModel::orderBy('kategori_id', 'desc')->first(); // Ambil kategori terakhir
        $lastKode = $lastKategori ? intval(substr($lastKategori->kategori_kode, 3)) : 0; // Ambil angka terakhir dari kode
        $newKode = 'KTG' . str_pad($lastKode + 1, 3, '0', STR_PAD_LEFT); // Buat kode baru dengan format KTG001, KTG002, dst.

        try {
            KategoriModel::create([
                'kategori_kode' => $newKode, // Simpan kode kategori
                'kategori_nama' => $request->kategori_nama, // Simpan nama kategori
            ]);

            return redirect('/kategori')->with('success', 'Kategori berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    // Untuk menampilkan detail kategori
    public function show($id)
    {
        $kategori = KategoriModel::findOrFail($id); // Ambil data kategori berdasarkan ID

        $breadcrumb = (object) [
            'title' => 'Detail Kategori',
            'list' => ['Home', 'Kategori', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail kategori'
        ];

        $activeMenu = 'kategori'; // Set menu yang sedang aktif

        return view('kategori.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategori' => $kategori // Kirim data kategori ke view
        ]);
    }

    // Untuk menampilkan form edit kategori
    public function edit($id)
    {
        $kategori = KategoriModel::findOrFail($id); // Ambil data kategori berdasarkan ID

        $breadcrumb = (object) [
            'title' => 'Edit Kategori',
            'list' => ['Home', 'Kategori', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit kategori'
        ];

        $activeMenu = 'kategori'; // Set menu yang sedang aktif

        return view('kategori.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategori' => $kategori // Kirim data kategori ke view
        ]);
    }

    // Untuk menyimpan perubahan data kategori
    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_nama' => 'required|string|max:100', // Validasi nama kategori
        ]);

        $kategori = KategoriModel::findOrFail($id);
        $kategori->update([
            'kategori_nama' => $request->kategori_nama
        ]);

        return redirect('/kategori')->with('success', 'Kategori berhasil diperbarui.');
    }

    // Untuk menghapus kategori
    public function destroy($id)
    {
        $kategori = KategoriModel::findOrFail($id);
        $kategori->delete();

        return redirect('/kategori')->with('success', 'Kategori berhasil dihapus.');
    }

    public function create_ajax()
    {
        return view('kategori.create_ajax');
    }

    public function store_ajax(Request $req)
    {
        if ($req->ajax() || $req->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|max:10',
                'kategori_nama' => 'required|string|max:100',
            ];

            $validator = Validator::make($req->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            KategoriModel::create($req->all());
            return response()->json([
                'status' => true,
                'message' => 'Data Berhasil Disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax($id)
    {
        $kategori = KategoriModel::findOrFail($id);

        return view('kategori.edit_ajax', [
            'kategori' => $kategori
        ]);
    }

    public function update_ajax(Request $req, $id)
    {
        if ($req->ajax() || $req->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|max:10',
                'kategori_nama' => 'required|string|max:100',
            ];

            $validator = Validator::make($req->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            KategoriModel::findOrFail($id)->update($req->all());
            return response()->json([
                'status' => true,
                'message' => 'Data Berhasil Diperbarui'
            ]);
        }
        redirect('/');
    }

    public function confirm_ajax($id)
    {
        $kategori = KategoriModel::findOrFail($id);

        return view('kategori.confirm_ajax', [
            'kategori' => $kategori
        ]);
    }

    public function delete_ajax($id)
    {
        if (request()->ajax()) {
            $kategori = KategoriModel::findOrFail($id);
            $kategori->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data Berhasil Dihapus'
            ]);
        }
        redirect('/');
    }
}
