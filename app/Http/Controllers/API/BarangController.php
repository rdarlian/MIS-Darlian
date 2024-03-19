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
        $draw = $request->input('draw');
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search');
        $order = $request->input('order', []);
        $id = $request->input('id');

        if ($id) {
            $barang = Barang::find($id);

            if ($barang) {
                return ResponseFormatter::success($barang, "Barang found");
            }
            return ResponseFormatter::error('barang not found');
        }

        // Base query
        $query = Barang::query();

        // Filtering
        if (!empty($search)) {
            $query->where('nama_barang', 'ilike', '%' . $search . '%');
        }
        if (!empty($order)) {

            $manualColumn = ["id", "nama_barang", "kode_barang", "stok", "harga", "user"];
            $query->orderBy($manualColumn[$order[0]['column']], $order[0]['dir']);
        }
        // Paginate the query
        $users = $query->offset($start)
            ->limit($length)
            ->get();
        // Total records without filtering
        $totalRecords = Barang::count();


        // Total records after filtering
        $filteredRecords = $query->count();


        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $users,
        ]);
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
