<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StokGudangController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | EMPAT KATEGORI GUDANG YANG SUDAH ADA
    |--------------------------------------------------------------------------
    */

    private function warehouseCategories()
    {
        return DB::table('categories')
            ->whereIn('name', [
                'Stok Cat',
                'Stok Electrical',
                'Bahan Kantor Workshop',
                'Stok Plumbing',
            ])
            ->orderBy('id')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | DATA FORM
    |--------------------------------------------------------------------------
    */

    private function formData()
    {
        $warehouseNames = [
            'Stok Cat',
            'Stok Electrical',
            'Bahan Kantor Workshop',
            'Stok Plumbing',
        ];

        return [
            'categories' => DB::table('categories')
                ->whereNotIn('name', $warehouseNames)
                ->orderBy('name')
                ->get(),

            'warehouseCategories' => $this->warehouseCategories(),

            'brands' => DB::table('brands')
                ->orderBy('name')
                ->get(),

            'suppliers' => DB::table('supplier')
                ->orderBy('name')
                ->get(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $warehouseCategories =$this->warehouseCategories();
        $warehouseIds =$warehouseCategories->pluck('id')->toArray();
        $selectedCategory =$request->input('warehouse_category_id');

        if ($request->filled('start_date') ||$request->filled('end_date')) {
            try {
                $startDate =$request->filled('start_date')
                    ? Carbon::parse($request->start_date)->startOfDay()
                    : Carbon::parse($request->end_date)->startOfMonth()->startOfDay();

                $endDate =$request->filled('end_date')
                    ? Carbon::parse($request->end_date)->endOfDay()
                    : Carbon::parse($request->start_date)->endOfMonth()->endOfDay();
            } catch (\Exception $e) {
                return back()->withErrors([
                    'date' => 'Format tanggal tidak valid.',
                ]);
            }

            $month = (int)$startDate->month;
            $year  = (int)$startDate->year;
        } else {
            $month = max(1, min(12, (int)$request->input('month', now()->month)));
            $year  = max(2000, min(2100, (int)$request->input('year', now()->year)));

            $startDate = Carbon::create($year, $month, 1)->startOfDay();$endDate   = Carbon::create($year,$month, 1)->endOfMonth()->endOfDay();
        }

        if ($endDate->lt($startDate)) {
            return back()->withErrors([
                'end_date' => 'Tanggal akhir tidak boleh lebih awal dari tanggal mulai.',
            ]);
        }

        if (
            $selectedCategory &&
            !in_array((int) $selectedCategory,$warehouseIds)
        ) {
            abort(404, 'Kategori gudang tidak ditemukan.');
        }

        /*
        |--------------------------------------------------------------------------
        | STOK AWAL SEBELUM PERIODE
        |--------------------------------------------------------------------------
        */

        $openingQuery = DB::table('inventory_transactions')
            ->select('material_id')
            ->selectRaw("
                SUM(
                    CASE
                        WHEN type = 'in' THEN quantity
                        WHEN type = 'out' THEN -quantity
                        ELSE 0
                    END
                ) AS opening_stock
            ")
            ->whereDate('transaction_date', '<', $startDate->toDateString())
            ->groupBy('material_id');

        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI MASUK DAN KELUAR DALAM PERIODE
        |--------------------------------------------------------------------------
        */

        $periodQuery = DB::table('inventory_transactions')
            ->select('material_id')
            ->selectRaw("
                SUM(
                    CASE WHEN type = 'in'
                    THEN quantity ELSE 0 END
                ) AS total_in
            ")
            ->selectRaw("
                SUM(
                    CASE WHEN type = 'out'
                    THEN quantity ELSE 0 END
                ) AS total_out
            ")
            ->whereDate('transaction_date', '>=', $startDate->toDateString())
            ->whereDate('transaction_date', '<=', $endDate->toDateString())
            ->groupBy('material_id');

        /*
        |--------------------------------------------------------------------------
        | TANGGAL TRANSAKSI TERAKHIR DALAM PERIODE
        |--------------------------------------------------------------------------
        */

        $periodDateQuery = DB::table('inventory_transactions')
            ->select('material_id')
            ->selectRaw('MAX(transaction_date) AS period_transaction_date')
            ->whereDate('transaction_date', '>=', $startDate->toDateString())
            ->whereDate('transaction_date', '<=', $endDate->toDateString())
            ->groupBy('material_id');

        /*
        |--------------------------------------------------------------------------
        | HARGA POKOK RATA-RATA
        |--------------------------------------------------------------------------
        */

        $costQuery = DB::table('inventory_transactions')
            ->select('material_id')
            ->selectRaw("
                SUM(
                    CASE WHEN type = 'in'
                    THEN total_price ELSE 0 END
                ) AS total_purchase
            ")
            ->selectRaw("
                SUM(
                    CASE WHEN type = 'in'
                    THEN quantity ELSE 0 END
                ) AS purchase_quantity
            ")
            ->groupBy('material_id');

        /*
        |--------------------------------------------------------------------------
        | DATA MATERIAL
        |--------------------------------------------------------------------------
        */

        $stocksQuery = DB::table('materials as m')
            ->leftJoinSub($openingQuery, 'o', function ($join) {$join->on('m.id', '=', 'o.material_id');
            })
            ->leftJoinSub($periodQuery, 'p', function ($join) {$join->on('m.id', '=', 'p.material_id');
            })
            ->leftJoinSub($periodDateQuery, 'pd', function ($join) {$join->on('m.id', '=', 'pd.material_id');
            })
            ->leftJoinSub($costQuery, 't', function ($join) {$join->on('m.id', '=', 't.material_id');
            })
            ->select(
                'm.id',
                'm.code',
                'm.name',
                'm.unit',
                'm.minimum_stock',
                'm.category_id',
                'm.warehouse_category_id'
            )
            ->selectRaw("COALESCE(pd.period_transaction_date, DATE(m.created_at)) AS tanggal_data")
            ->selectRaw("COALESCE(o.opening_stock, 0) AS opening_stock")
            ->selectRaw("COALESCE(p.total_in, 0) AS total_in")
            ->selectRaw("COALESCE(p.total_out, 0) AS total_out")
            ->selectRaw("
                COALESCE(o.opening_stock, 0)
                + COALESCE(p.total_in, 0)
                - COALESCE(p.total_out, 0)
                AS stock
            ")
            ->selectRaw("
                CASE
                    WHEN COALESCE(t.purchase_quantity, 0) > 0
                    THEN t.total_purchase / t.purchase_quantity
                    ELSE 0
                END AS average_cost
            ")
            ->selectRaw("
                (
                    COALESCE(o.opening_stock, 0)
                    + COALESCE(p.total_in, 0)
                    - COALESCE(p.total_out, 0)
                ) *
                CASE
                    WHEN COALESCE(t.purchase_quantity, 0) > 0
                    THEN t.total_purchase / t.purchase_quantity
                    ELSE 0
                END AS stock_value
            ")
            ->whereIn('m.warehouse_category_id', $warehouseIds);

        if ($selectedCategory) {
            $stocksQuery->where('m.warehouse_category_id',$selectedCategory);
        }

        // Tampilkan material yang memang dibuat pada periode ini atau memiliki transaksi pada periode ini
        $stocksQuery->where(function ($query) use ($startDate, $endDate) {$query->whereNotNull('pd.period_transaction_date')
                ->orWhere(function ($sub) use ($startDate,$endDate) {
                    $sub->whereDate('m.created_at', '>=',$startDate->toDateString())
                        ->whereDate('m.created_at', '<=', $endDate->toDateString());
                });
        });

        $stocks =$stocksQuery
            ->orderByDesc('tanggal_data')
            ->orderBy('m.name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | STATUS STOK
        |--------------------------------------------------------------------------
        */

        foreach ($stocks as$stock) {
            if ($stock->stock <= 0) {$stock->status = 'Habis';
            } elseif ($stock->stock <= $stock->minimum_stock) {$stock->status = 'Menipis';
            } else {
                $stock->status = 'Aman';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalMaterials = $stocks->count();$totalStock     = $stocks->sum('stock');$lowStock       = $stocks->where('status', 'Menipis')->count();$emptyStock     = $stocks->where('status', 'Habis')->count();$lowOrEmpty     = $lowStock +$emptyStock;
        $totalValue     =$stocks->sum('stock_value');

        return view('stokgudang', compact(
            'stocks',
            'warehouseCategories',
            'selectedCategory',
            'month',
            'year',
            'startDate',
            'endDate',
            'totalMaterials',
            'totalStock',
            'lowStock',
            'emptyStock',
            'lowOrEmpty',
            'totalValue'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'stokgudang.create',
            $this->formData()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $warehouseIds =$this->warehouseCategories()
            ->pluck('id')
            ->toArray();

        $validated =$request->validate([
            'code' => 'required|string|max:255|unique:materials,code',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'warehouse_category_id' => [
                'required',
                Rule::in($warehouseIds),
            ],
            'brand_id' => 'required|exists:brands,id',
            'specification' => 'nullable|string|max:255',
            'series' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'motif' => 'nullable|string|max:255',
            'finish' => 'nullable|string|max:255',
            'unit' => 'required|string|max:30',
            'minimum_stock' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'quantity' => 'required|numeric|gt:0',
            'supplier_id' => 'required|exists:supplier,id',
            'unit_price' => 'required|numeric|min:0',
            'transaction_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $transactionDate =$validated['transaction_date'] ?? now()->toDateString();

        DB::transaction(function () use ($validated, $transactionDate) {$materialId = DB::table('materials')->insertGetId([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'warehouse_category_id' => $validated['warehouse_category_id'],
                'brand_id' => $validated['brand_id'],
                'specification' => $validated['specification'] ?? '',
                'series' => $validated['series'] ?? '',
                'size' => $validated['size'] ?? '',
                'color' => $validated['color'] ?? '',
                'motif' => $validated['motif'] ?? '',
                'finish' => $validated['finish'] ?? '',
                'unit' => $validated['unit'],
                'minimum_stock' => $validated['minimum_stock'],
                'description' => $validated['description'] ?? '',
                'stok' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('inventory_transactions')->insert([
                'material_id' => $materialId,
                'supplier_id' => $validated['supplier_id'],
                'type' => 'in',
                'quantity' => $validated['quantity'],
                'unit_price' => $validated['unit_price'],
                'total_price' => $validated['quantity'] *$validated['unit_price'],
                'transaction_date' => $transactionDate,
                'reference_number' => (string) Str::uuid(),
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::table('materials')
                ->where('id', $materialId)
                ->update([
                    'stok' => $validated['quantity'],
                ]);
        });

        return redirect()
            ->route('stokgudang.index')
            ->with('success', 'Barang berhasil ditambahkan ke Stok Gudang.');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $material = DB::table('materials')
            ->where('id', $id)
            ->first();

        if (!$material) {
            abort(404);
        }

        $data =$this->formData();
        $data['material'] =$material;

        return view('stokgudang.edit', $data);
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request,$id)
    {
        $material = DB::table('materials')
            ->where('id', $id)
            ->first();

        if (!$material) {
            abort(404);
        }

        $warehouseIds =$this->warehouseCategories()
            ->pluck('id')
            ->toArray();

        $validated =$request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('materials', 'code')->ignore($id),
            ],
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'warehouse_category_id' => [
                'required',
                Rule::in($warehouseIds),
            ],
            'brand_id' => 'required|exists:brands,id',
            'specification' => 'nullable|string|max:255',
            'series' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'motif' => 'nullable|string|max:255',
            'finish' => 'nullable|string|max:255',
            'unit' => 'required|string|max:30',
            'minimum_stock' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        DB::table('materials')
            ->where('id', $id)
            ->update([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'warehouse_category_id' => $validated['warehouse_category_id'],
                'brand_id' => $validated['brand_id'],
                'specification' => $validated['specification'] ?? '',
                'series' => $validated['series'] ?? '',
                'size' => $validated['size'] ?? '',
                'color' => $validated['color'] ?? '',
                'motif' => $validated['motif'] ?? '',
                'finish' => $validated['finish'] ?? '',
                'unit' => $validated['unit'],
                'minimum_stock' => $validated['minimum_stock'],
                'description' => $validated['description'] ?? '',
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('stokgudang.index')
            ->with('success', 'Data material berhasil diperbarui.');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $material = DB::table('materials')
            ->where('id', $id)
            ->first();

        if (!$material) {
            abort(404);
        }

        $hasTransactions = DB::table('inventory_transactions')
            ->where('material_id', $id)
            ->exists();

        if ($hasTransactions) {
            return redirect()
                ->route('stokgudang.index')
                ->with(
                    'error',
                    'Barang tidak dapat dihapus karena sudah memiliki riwayat transaksi.'
                );
        }

        DB::table('materials')
            ->where('id', $id)
            ->delete();

        return redirect()
            ->route('stokgudang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}