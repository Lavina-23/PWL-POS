<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Models\LevelModel; // Import the LevelModel class
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Daftar level pengguna'
        ];

        $activeMenu = 'level'; // Set menu yang sedang aktif

        return view('level.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    // menyediakan data level dalam format JSON untuk DataTables, termasuk filtering dan searching
    public function list(Request $request)
    {
        $levels = LevelModel::query();

        // Filter berdasarkan level_kode jika ada
        if ($request->has('level_kode') && $request->level_kode) {
            $levels->where('level_kode', $request->level_kode);
        }

        return DataTables::of($levels)
            ->addIndexColumn() // Tambahkan nomor urut
            ->addColumn('aksi', function ($level) {  // menambahkan kolom aksi
                $btn  = '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/delete_ajax') . '\')"  class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn;
            })
            ->rawColumns(['aksi']) // Pastikan kolom aksi dirender sebagai HTML
            ->make(true);
    }

    // menampilkan detail level
    public function show($id)
    {
        $level = LevelModel::findOrFail($id); // Ambil data level berdasarkan ID

        $breadcrumb = (object) [
            'title' => 'Detail Level',
            'list' => ['Home', 'Level', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail level'
        ];

        $activeMenu = 'level'; // Set menu yang sedang aktif

        return view('level.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'level' => $level // Tambahkan variabel $level ke array data
        ]);
    }

    // Untuk menampilkan form edit level:
    public function edit($id)
    {
        $level = LevelModel::findOrFail($id); // Ambil data level berdasarkan ID

        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit level'
        ];

        $activeMenu = 'level'; // Set menu yang sedang aktif

        return view('level.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'level' => $level // Kirim data level ke view
        ]);
    }

    // Untuk menyimpan perubahan data level
    public function update(Request $request, $id)
    {
        $request->validate([
            'level_nama' => 'required|string|max:100', // Validasi nama level
            'level_kode' => 'required|string|max:10', // Validasi kode level
        ]);

        $level = LevelModel::findOrFail($id);
        $level->update([
            'level_nama' => $request->level_nama,
            'level_kode' => $request->level_kode
        ]);

        return redirect('/level')->with('success', 'Level berhasil diperbarui.');
    }

    // Untuk menghapus level
    public function destroy($id)
    {
        $level = LevelModel::findOrFail($id);
        $level->delete();

        return redirect('/level')->with('success', 'Level berhasil dihapus.');
    }

    // Untuk menampilkan form tambah level
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah level baru'
        ];

        $activeMenu = 'level'; // Set menu yang sedang aktif

        return view('level.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    // Untuk menyimpan data level baru
    public function store(Request $request)
    {
        $request->validate([
            'level_nama' => 'required|string|max:100', // Validasi nama level
            'level_kode' => 'required|string|max:10', // Validasi kode level
        ]);

        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama
        ]);

        return redirect('/level')->with('success', 'Level berhasil ditambahkan.');
    }

    public function create_ajax()
    {
        return view('level.create_ajax');
    }

    public function store_ajax(Request $req)
    {
        if ($req->ajax() || $req->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|max:10',
                'level_nama' => 'required|string|max:100',
            ];

            $validator = Validator::make($req->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            LevelModel::create($req->all());
            return response()->json([
                'status' => true,
                'message' => 'Data Berhasil Disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $level = LevelModel::findOrFail($id);

        return view('level.edit_ajax', [
            'level' => $level
        ]);
    }

    public function update_ajax(Request $req, $id)
    {
        if ($req->ajax() || $req->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|max:10',
                'level_nama' => 'required|string|max:100',
            ];

            $validator = Validator::make($req->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            LevelModel::findOrFail($id)->update($req->all());
            return response()->json([
                'status' => true,
                'message' => 'Data Berhasil Disimpan'
            ]);
        }
        redirect('/');
    }

    public function confirm_ajax($id)
    {
        $level = LevelModel::find($id);

        return view('level.confirm_ajax', [
            'level' => $level
        ]);
    }

    public function delete_ajax(Request $req, $id)
    {
        if ($req->ajax() || $req->wantsJson()) {
            $level = LevelModel::find($id);
            if ($level) {
                $level->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data Berhasil Dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data Tidak Ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
}
