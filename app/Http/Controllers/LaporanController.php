<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan');
    }

    /**
     * Ambil data laporan sesuai kategori & periode
     */
    private function getReportData(string $type, ?string $startDate, ?string $endDate): array
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $end   = $endDate ? Carbon::parse($endDate)->endOfDay() : null;

        if ($type === 'pembelian') {
            $headings = [
                'No.',
                'Tanggal',
                'No. Invoice',
                'Supplier',
                'Proyek',
                'Total Unit',
                'Ongkos Kirim (Rp)',
                'Total Pembelian (Rp)',
                'Status',
            ];

            // Cek apakah menggunakan tabel purchases atau langsung inventory_transactions
            if (Schema::hasTable('purchases')) {
                $query = DB::table('purchases as p')
                    ->leftJoin('supplier as s', 'p.supplier_id', '=', 's.id')
                    ->select(
                        'p.id',
                        'p.purchase_date',
                        'p.invoice_number',
                        's.name as supplier_name',
                        'p.project_name',
                        'p.shipping_cost',
                        'p.grand_total',
                        'p.status'
                    );

                if ($start) {
                    $query->whereDate('p.purchase_date', '>=', $start->toDateString());
                }
                if ($end) {
                    $query->whereDate('p.purchase_date', '<=', $end->toDateString());
                }

                $records = $query->orderByDesc('p.purchase_date')->get();

                $rows = [];
                foreach ($records as $i => $row) {
                    $totalUnit = Schema::hasTable('purchase_items')
                        ? DB::table('purchase_items')->where('purchase_id', $row->id)->sum('quantity')
                        : 0;

                    $rows[] = [
                        $i + 1,
                        $row->purchase_date ? Carbon::parse($row->purchase_date)->format('d-m-Y') : '-',
                        $row->invoice_number ?? '-',
                        $row->supplier_name ?? '-',
                        $row->project_name ?: '-',
                        number_format((float) $totalUnit, 2, ',', '.'),
                        'Rp ' . number_format((float) ($row->shipping_cost ?? 0), 0, ',', '.'),
                        'Rp ' . number_format((float) ($row->grand_total ?? 0), 0, ',', '.'),
                        $row->status ?? 'Tercatat',
                    ];
                }
            } else {
                // Fallback jika transaksi pembelian disimpan langsung di inventory_transactions
                $query = DB::table('inventory_transactions as it')
                    ->leftJoin('materials as m', 'it.material_id', '=', 'm.id')
                    ->leftJoin('supplier as s', 'it.supplier_id', '=', 's.id')
                    ->where('it.type', 'in')
                    ->select(
                        'it.transaction_date',
                        'it.reference_number',
                        's.name as supplier_name',
                        'm.name as material_name',
                        'it.quantity',
                        'it.unit_price',
                        'it.total_price'
                    );

                if ($start) {
                    $query->whereDate('it.transaction_date', '>=', $start->toDateString());
                }
                if ($end) {
                    $query->whereDate('it.transaction_date', '<=', $end->toDateString());
                }

                $records = $query->orderByDesc('it.transaction_date')->get();

                $headings = [
                    'No.',
                    'Tanggal',
                    'No. Referensi',
                    'Supplier',
                    'Material',
                    'Jumlah',
                    'Harga Satuan (Rp)',
                    'Total (Rp)',
                    'Status',
                ];

                $rows = [];
                foreach ($records as $i => $row) {
                    $rows[] = [
                        $i + 1,
                        $row->transaction_date ? Carbon::parse($row->transaction_date)->format('d-m-Y') : '-',
                        $row->reference_number ?? '-',
                        $row->supplier_name ?? '-',
                        $row->material_name ?? '-',
                        number_format((float) $row->quantity, 2, ',', '.'),
                        'Rp ' . number_format((float) $row->unit_price, 0, ',', '.'),
                        'Rp ' . number_format((float) $row->total_price, 0, ',', '.'),
                        'Masuk',
                    ];
                }
            }

            return [
                'title'    => 'Laporan Pembelian & Stok',
                'headings' => $headings,
                'rows'     => $rows,
            ];
        }

        if ($type === 'stok') {
            $headings = [
                'No.',
                'Kode Barang',
                'Nama Material',
                'Stok Awal',
                'Stok Masuk',
                'Stok Keluar',
                'Stok Tersedia',
                'Satuan',
                'Harga Pokok (Rp)',
                'Nilai Stok (Rp)',
                'Status',
            ];

            $warehouseIds = DB::table('categories')
                ->whereIn('name', [
                    'Stok Cat',
                    'Stok Electrical',
                    'Bahan Kantor Workshop',
                    'Stok Plumbing',
                ])
                ->pluck('id')
                ->toArray();

            $openingQuery = DB::table('inventory_transactions')
                ->select('material_id')
                ->selectRaw("SUM(CASE WHEN type = 'in' THEN quantity WHEN type = 'out' THEN -quantity ELSE 0 END) AS opening_stock")
                ->when($start, fn($q) => $q->whereDate('transaction_date', '<', $start->toDateString()))
                ->when(!$start, fn($q) => $q->whereRaw('1 = 0'))
                ->groupBy('material_id');

            $periodQuery = DB::table('inventory_transactions')
                ->select('material_id')
                ->selectRaw("SUM(CASE WHEN type = 'in' THEN quantity ELSE 0 END) AS total_in")
                ->selectRaw("SUM(CASE WHEN type = 'out' THEN quantity ELSE 0 END) AS total_out")
                ->when($start, fn($q) => $q->whereDate('transaction_date', '>=', $start->toDateString()))
                ->when($end, fn($q) => $q->whereDate('transaction_date', '<=', $end->toDateString()))
                ->groupBy('material_id');

            $costQuery = DB::table('inventory_transactions')
                ->select('material_id')
                ->selectRaw("SUM(CASE WHEN type = 'in' THEN total_price ELSE 0 END) AS total_purchase")
                ->selectRaw("SUM(CASE WHEN type = 'in' THEN quantity ELSE 0 END) AS purchase_quantity")
                ->groupBy('material_id');

            $materials = DB::table('materials as m')
                ->leftJoinSub($openingQuery, 'o', fn($j) => $j->on('m.id', '=', 'o.material_id'))
                ->leftJoinSub($periodQuery, 'p', fn($j) => $j->on('m.id', '=', 'p.material_id'))
                ->leftJoinSub($costQuery, 't', fn($j) => $j->on('m.id', '=', 't.material_id'))
                ->select(
                    'm.code',
                    'm.name',
                    'm.unit',
                    'm.minimum_stock'
                )
                ->selectRaw('COALESCE(o.opening_stock, 0) AS opening_stock')
                ->selectRaw('COALESCE(p.total_in, 0) AS total_in')
                ->selectRaw('COALESCE(p.total_out, 0) AS total_out')
                ->selectRaw('COALESCE(o.opening_stock, 0) + COALESCE(p.total_in, 0) - COALESCE(p.total_out, 0) AS stock')
                ->selectRaw('CASE WHEN COALESCE(t.purchase_quantity, 0) > 0 THEN t.total_purchase / t.purchase_quantity ELSE 0 END AS average_cost')
                ->whereIn('m.warehouse_category_id', $warehouseIds)
                ->orderBy('m.name')
                ->get();

            $rows = [];
            foreach ($materials as $i => $m) {
                $status = $m->stock <= 0 ? 'Habis' : ($m->stock <= $m->minimum_stock ? 'Menipis' : 'Aman');
                $stockValue = $m->stock * $m->average_cost;

                $rows[] = [
                    $i + 1,
                    $m->code,
                    $m->name,
                    number_format((float) $m->opening_stock, 2, ',', '.'),
                    number_format((float) $m->total_in, 2, ',', '.'),
                    number_format((float) $m->total_out, 2, ',', '.'),
                    number_format((float) $m->stock, 2, ',', '.'),
                    $m->unit,
                    'Rp ' . number_format((float) $m->average_cost, 0, ',', '.'),
                    'Rp ' . number_format((float) $stockValue, 0, ',', '.'),
                    $status,
                ];
            }

            return [
                'title'    => 'Laporan Stok Gudang',
                'headings' => $headings,
                'rows'     => $rows,
            ];
        }

        // Placeholder untuk kategori lain (RAP, Keuangan, Hutang)
        return [
            'title'    => 'Laporan ' . ucfirst($type),
            'headings' => ['No.', 'Keterangan'],
            'rows'     => [],
        ];
    }

    /**
     * Endpoint Preview JSON untuk tabel di halaman Laporan
     */
    public function preview(Request $request)
    {
        $type      = $request->input('report_type', 'pembelian');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $data = $this->getReportData($type, $startDate, $endDate);

        return response()->json($data);
    }

    /**
     * Endpoint Download Excel / CSV / PDF
     */
    public function export(Request $request)
    {
        $type      = $request->input('report_type', 'pembelian');
        $format    = $request->input('format', 'excel');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $data     = $this->getReportData($type, $startDate, $endDate);
        $slug     = str_replace(' ', '_', strtolower($data['title']));
        $dateTag  = now()->format('Ymd_His');

        if ($format === 'csv') {
            return Excel::download(
                new LaporanExport($data['headings'], $data['rows']),
                "{$slug}_{$dateTag}.csv",
                \Maatwebsite\Excel\Excel::CSV
            );
        }

        if ($format === 'pdf') {
            $periodText = ($startDate && $endDate)
                ? Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y')
                : 'Semua Periode';

            $pdf = Pdf::loadView('laporan_pdf', [
                'title'      => $data['title'],
                'headings'   => $data['headings'],
                'rows'       => $data['rows'],
                'periodText' => $periodText,
            ])->setPaper('a4', 'landscape');

            return $pdf->download("{$slug}_{$dateTag}.pdf");
        }

        // Default: Excel (.xlsx)
        return Excel::download(
            new LaporanExport($data['headings'], $data['rows']),
            "{$slug}_{$dateTag}.xlsx"
        );
    }
}