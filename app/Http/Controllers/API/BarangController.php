<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBarangRequest;
use App\Http\Requests\UpdateBarangRequest;

class BarangController extends Controller
{
    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $kode_barang = $request->input('kode_barang');
        $nama_barang = $request->input('nama_barang');
        $limit = $request->input('limit', 100);
        $user = $request->input('user');

        //get single data
        if ($id) {
            $barang = Barang::find($id);

            if ($barang) {
                return ResponseFormatter::success($barang, "Barang found");
            }
            return ResponseFormatter::error('barang not found');
        }
        //get multiple data
        $barangs = DB::table('barangs');
        // $barangs = Barang::query()->where('user', $request->user);

        if ($nama_barang) {
            $barangs->where('nama_barang', 'like', '%' . $nama_barang . '%');
        }
        if ($kode_barang) {
            $barangs->where('kode$kode_barang', 'like', '%' . $kode_barang . '%');
        }
        if ($id) {
            $barangs->where('id', 'like', '%' . $id . '%');
        }

        return ResponseFormatter::success($barangs->orderBy('nama_barang', 'asc')->paginate($limit), 'barang Found');
    }

    public function create(CreateBarangRequest $request)
    {
        try {
            $barang = Barang::create([
                'nama_barang' => $request->nama_barang,
                'kode_barang' => $request->kode_barang,
                'stok' => $request->stok,
                'harga' => $request->harga,
                'user' => $request->user,
            ]);

            if (!$barang) {
                throw new Exception('Barang not created');
            }

            return ResponseFormatter::success($barang, 'Barang created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function update(UpdateBarangRequest $request, $id)
    {
        try {
            $barang = Barang::find($id);

            if (!$barang) {
                throw new Exception('Barang not created');
            }

            //update barang
            $barang->update([
                'nama_barang' => $request->nama_barang,
                'kode_barang' => $request->kode_barang,
                'stok' => $request->stok,
                'harga' => $request->harga,
                'user' => $request->user,

            ]);

            return ResponseFormatter::success($barang, 'Barang updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
    public function destroy($id)
    {
        try {
            //Get Barang
            $barang = Barang::find($id);

            //check if barang exists
            if (!$barang) {
                throw new Exception('Barang not Found');
            }

            //Delete Barang
            $barang->delete();

            return ResponseFormatter::success('Barang deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
