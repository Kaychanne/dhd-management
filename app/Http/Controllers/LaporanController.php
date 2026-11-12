<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class LaporanController extends Controller
{

    public function index()
    {
        return view('laporan');
    }
    public function preview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_type' => 'required|in:rap,keuangan,hutang,stok',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $type = $request->report_type;

        switch ($type) {
            case 'stok':
                $data = $this->getLaporanStok();
                break;

            case 'rap':
                $data = [];
                break;

            case 'keuangan':
                $data = [];
                break;

            case 'hutang':
                $data = [];
                break;

            default:
                $data = [];
        }

        return response()->json([
            'success' => true,
            'report_type' => $type,
            'data' => $data,
            'total' => count($data),
        ]);
    }

    /**
     * DATA LAPORAN STOK GUDANG
     */
    private function getLaporanStok()
    {
        $transactionData = DB::table('inventory_transactions')
            ->select('material_id')
            ->selectRaw("
                SUM(
                    CASE WHEN type = 'in'
                    THEN quantity ELSE 0 END
                ) as total_in
            ")
            ->selectRaw("
                SUM(
                    CASE WHEN type = 'out'
                    THEN quantity ELSE 0 END
                ) as total_out
            ")
            ->groupBy('material_id');

        $stocks = DB::table('materials as m')
            ->leftJoinSub($transactionData, 't', function ($join) {
                $join->on('m.id', '=', 't.material_id');
            })
            ->select(
                'm.id',
                'm.code',
                'm.name',
                'm.unit',
                'm.minimum_stock'
            )
            ->selectRaw('COALESCE(t.total_in, 0) as total_in')
            ->selectRaw('COALESCE(t.total_out, 0) as total_out')
            ->selectRaw("
                COALESCE(t.total_in, 0) -
                COALESCE(t.total_out, 0) as stock
            ")
            ->orderBy('m.name')
            ->get();

        foreach ($stocks as $stock) {
            if ($stock->stock <= 0) {
                $stock->status = 'Habis';
            } elseif ($stock->stock <= $stock->minimum_stock) {
                $stock->status = 'Menipis';
            } else {
                $stock->status = 'Aman';
            }
        }

        return $stocks;
    }

    /**
     * EKSPOR LAPORAN CSV
     */
    public function exportCsv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_type' => 'required|in:rap,keuangan,hutang,stok',
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Kategori laporan tidak valid.');
        }

        if ($request->report_type !== 'stok') {
            return back()->with(
                'error',
                'Laporan kategori ini belum terhubung dengan database.'
            );
        }

        $data = $this->getLaporanStok();

        $filename = 'laporan-stok-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($data) {
            $file = fopen('php://output', 'w');

            // BOM agar karakter CSV terbaca baik di Excel
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'No',
                'Kode Barang',
                'Nama Material',
                'Stok Masuk',
                'Stok Keluar',
                'Stok Akhir',
                'Satuan',
                'Minimum Stok',
                'Status'
            ], ';');

            foreach ($data as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    $item->code,
                    $item->name,
                    $item->total_in,
                    $item->total_out,
                    $item->stock,
                    $item->unit,
                    $item->minimum_stock,
                    $item->status
                ], ';');
            }

            fclose($file);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}



