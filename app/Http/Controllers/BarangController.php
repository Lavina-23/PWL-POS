<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use Illuminate\Http\Request;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];

        $page = (object) [
            'title' => 'Daftar barang'
        ];

        $activeMenu = 'barang'; // Set menu yang sedang aktif

        // Ambil semua kategori untuk dropdown filter
        $kategoris = KategoriModel::all();

        return view('barang.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategoris' => $kategoris
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah barang baru'
        ];

        $activeMenu = 'barang'; // Set menu yang sedang aktif

        // Ambil semua kategori untuk dropdown
        $kategoris = KategoriModel::all();

        return view('barang.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategoris' => $kategoris
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'kategori_id' => 'required|exists:m_kategori,kategori_id',
        ]);

        // Generate kode barang otomatis
        $lastBarang = BarangModel::orderBy('barang_id', 'desc')->first();
        $lastKode = $lastBarang ? intval(substr($lastBarang->barang_kode, 3)) : 0;
        $newKode = 'BRG' . str_pad($lastKode + 1, 3, '0', STR_PAD_LEFT);

        try {
            BarangModel::create([
                'barang_kode' => $newKode,
                'barang_nama' => $request->barang_nama,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'kategori_id' => $request->kategori_id,
            ]);

            return redirect('/barang')->with('success', 'Barang berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $barang = BarangModel::with('kategori')->findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Detail Barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail barang'
        ];

        $activeMenu = 'barang'; // Set menu yang sedang aktif

        return view('barang.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'barang' => $barang
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $barang = BarangModel::findOrFail($id);
        $kategoris = KategoriModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Barang',
            'list' => ['Home', 'Barang', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit barang'
        ];

        $activeMenu = 'barang'; // Set menu yang sedang aktif

        return view('barang.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'barang' => $barang,
            'kategoris' => $kategoris
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'kategori_id' => 'required|exists:m_kategori,kategori_id',
        ]);

        $barang = BarangModel::findOrFail($id);

        try {
            $barang->update([
                'barang_nama' => $request->barang_nama,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
                'kategori_id' => $request->kategori_id,
            ]);

            return redirect('/barang')->with('success', 'Barang berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $barang = BarangModel::findOrFail($id);
            $barang->delete();

            return redirect('/barang')->with('success', 'Barang berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect('/barang')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get data for DataTables
     */
    public function list(Request $request)
    {
        $barang = BarangModel::with('kategori');

        // Filter berdasarkan kategori_id jika ada
        if ($request->has('kategori_id') && $request->kategori_id) {
            $barang->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan barang_nama jika ada
        if ($request->has('barang_nama') && $request->barang_nama) {
            $barang->where('barang_nama', 'like', '%' . $request->barang_nama . '%');
        }

        return DataTables::of($barang)
            ->addIndexColumn() // Tambahkan nomor urut
            ->addColumn('kategori_nama', function ($barang) {
                return $barang->kategori->kategori_nama;
            })
            ->addColumn('harga_beli_rp', function ($barang) {
                return 'Rp ' . number_format($barang->harga_beli, 0, ',', '.');
            })
            ->addColumn('harga_jual_rp', function ($barang) {
                return 'Rp ' . number_format($barang->harga_jual, 0, ',', '.');
            })
            ->addColumn('aksi', function ($barang) {  // menambahkan kolom aksi
                $btn  = '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn;
            })
            ->rawColumns(['aksi']) // Pastikan kolom aksi dirender sebagai HTML
            ->make(true);
    }

    public function create_ajax()
    {
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
        return view('barang.create_ajax', [
            'kategori' => $kategori
        ]);
    }

    public function store_ajax(Request $req)
    {
        if ($req->ajax() || $req->wantsJson()) {
            $rules = [
                'kategori_id' => 'required|exists:m_kategori,kategori_id',
                'barang_nama' => 'required|string|max:100',
                'harga_beli' => 'required|numeric|min:0',
                'harga_jual' => 'required|numeric|min:0',
            ];

            $validator = Validator::make($req->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $lastBarang = BarangModel::orderBy('barang_id', 'desc')->first();
            $lastKode = $lastBarang ? intval(substr($lastBarang->barang_kode, 3)) : 0;
            $newKode = 'BRG' . str_pad($lastKode + 1, 3, '0', STR_PAD_LEFT);

            BarangModel::create([
                'kategori_id' => $req->kategori_id,
                'barang_kode' => $newKode,
                'barang_nama' => $req->barang_nama,
                'harga_beli' => $req->harga_beli,
                'harga_jual' => $req->harga_jual,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data Berhasil Disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax($id)
    {
        $barang = BarangModel::findOrFail($id);
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();

        return view('barang.edit_ajax', [
            'barang' => $barang,
            'kategori' => $kategori
        ]);
    }

    public function update_ajax(Request $req, $id)
    {
        if ($req->ajax() || $req->wantsJson()) {
            $rules = [
                'kategori_id' => 'required|exists:m_kategori,kategori_id',
                'barang_nama' => 'required|string|max:100',
                'harga_beli' => 'required|numeric|min:0',
                'harga_jual' => 'required|numeric|min:0',
            ];

            $validator = Validator::make($req->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $barang = BarangModel::findOrFail($id);
            $barang->update([
                'kategori_id' => $req->kategori_id,
                'barang_nama' => $req->barang_nama,
                'harga_beli' => $req->harga_beli,
                'harga_jual' => $req->harga_jual,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data Berhasil Diperbarui'
            ]);
        }
        redirect('/');
    }

    public function confirm_ajax($id)
    {
        $barang = BarangModel::findOrFail($id);

        return view('barang.confirm_ajax', [
            'barang' => $barang
        ]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $barang = BarangModel::find($id);

            if ($barang) {
                $barang->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
}
