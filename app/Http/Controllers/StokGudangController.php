<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

use App\Models\StokGudang;
use App\Models\Material;
use App\Models\Supplier;
use App\Models\Category;
class StokGudangController extends Controller{
    public function index()
    {
        $transactionData = DB::table('inventory_transactions')
            ->select('material_id')
            ->selectRaw("
                SUM(CASE WHEN type = 'in'
                    THEN quantity ELSE 0 END) as total_in
            ")
            ->selectRaw("
                SUM(CASE WHEN type = 'out'
                    THEN quantity ELSE 0 END) as total_out
            ")
            ->selectRaw("
                SUM(CASE WHEN type = 'in'
                    THEN total_price ELSE 0 END) as total_purchase
            ")
            ->selectRaw("
                SUM(CASE WHEN type = 'in'
                    THEN quantity ELSE 0 END) as purchase_quantity
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
                'm.minimum_stock',
                'm.stok'
            )
            ->selectRaw('COALESCE(t.total_in, 0) as total_in')
            ->selectRaw('COALESCE(t.total_out, 0) as total_out')
            ->selectRaw("
                COALESCE(t.total_in, 0) -
                COALESCE(t.total_out, 0) as stock
            ")
            ->selectRaw("
                CASE
                    WHEN COALESCE(t.purchase_quantity, 0) > 0
                    THEN t.total_purchase / t.purchase_quantity
                    ELSE 0
                END as average_cost
            ")
            ->selectRaw("
                (
                    COALESCE(t.total_in, 0) -
                    COALESCE(t.total_out, 0)
                ) *
                CASE
                    WHEN COALESCE(t.purchase_quantity, 0) > 0
                    THEN t.total_purchase / t.purchase_quantity
                    ELSE 0
                END as stock_value
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

        $totalMaterials = $stocks->count();
        $totalStock = $stocks->sum('stock');

        $lowStock = $stocks->where('status', 'Menipis')->count();
        $emptyStock = $stocks->where('status', 'Habis')->count();

        $totalValue = $stocks->sum('stock_value');

        return view('stokgudang', compact(
            'stocks',
            'totalMaterials',
            'totalStock',
            'lowStock',
            'emptyStock',
            'totalValue'
        ));
    }

    /**
     * DATA UNTUK FORM
     */
    private function getFormData()
    {
        return [
            'categories' => DB::table('categories')->get(),
            'brands' => DB::table('brands')->get(),
            'suppliers' => DB::table('supplier')->get(),
        ];
    }
    public function create()
    {
        return view(
            'stokgudang.create',
            $this->getFormData()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                'unique:materials,code',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'brand_id' => [
                'nullable',
                'exists:brands,id',
            ],

            'specification' => [
                'nullable',
                'string',
                'max:255',
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'minimum_stock' => [
                'required',
                'numeric',
                'min:0',
            ],

            'supplier_id' => [
                'required',
                'exists:supplier,id',
            ],

            'unit_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'transaction_date' => [
                'required',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use ($validated) {

            // SIMPAN MASTER MATERIAL
            $materialId = DB::table('materials')->insertGetId([
                'code' => $validated['code'],
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],

                'brand_id' => $validated['brand_id'] ?? null,

                'specification' => $validated['specification'] ?? null,
                'unit' => $validated['unit'],
                'description' => $validated['description'] ?? null,
                'minimum_stock' => $validated['minimum_stock'],
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
                'total_price' =>
                    $validated['quantity'] * $validated['unit_price'],
                'transaction_date' => $validated['transaction_date'],
                'reference_number' => (string) Str::uuid(),
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()
            ->route('stokgudang.index')
            ->with('success', 'Barang dan stok awal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $material = Material::with('category')->findOrFail($id);

        $categories = Category::orderBy('name')->get();

        $suppliers = Supplier::orderBy('name')->get();

        $initialTransaction = StokGudang::where('material_id', $material->id)
            ->where('type', 'in')
            ->orderBy('transaction_date', 'asc')
            ->first();

        return view('stokgudang.edit', compact(
            'material',
            'categories',
            'suppliers',
            'initialTransaction'
        ));
    }

    public function update(Request $request, $id)
    {
        $material = DB::table('materials')
            ->where('id', $id)
            ->first();

        if (!$material) {
            abort(404, 'Material tidak ditemukan.');
        }

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('materials', 'code')->ignore($id),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'specification' => [
                'nullable',
                'string',
                'max:255',
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'quantity' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'minimum_stock' => [
                'required',
                'numeric',
                'min:0',
            ],

            'supplier_id' => [
                'required',
                'exists:supplier,id',
            ],

            'unit_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'transaction_date' => [
                'required',
                'date',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use ($validated, $id) {


            DB::table('materials')
                ->where('id', $id)
                ->update([
                    'code' => $validated['code'],
                    'name' => $validated['name'],
                    'category_id' => $validated['category_id'],
                    'specification' => $validated['specification'] ?? null,
                    'unit' => $validated['unit'],
                    'description' => $validated['description'] ?? null,
                    'minimum_stock' => $validated['minimum_stock'],
                    'updated_at' => now(),
                ]);


            $initialTransaction = DB::table('inventory_transactions')
                ->where('material_id', $id)
                ->where('type', 'in')
                ->orderBy('transaction_date')
                ->orderBy('id')
                ->first();

            $transactionData = [
                'material_id' => $id,
                'supplier_id' => $validated['supplier_id'],
                'type' => 'in',
                'quantity' => $validated['quantity'],
                'unit_price' => $validated['unit_price'],
                'total_price' =>
                    $validated['quantity'] * $validated['unit_price'],
                'transaction_date' => $validated['transaction_date'],
                'notes' => $validated['notes'] ?? null,
            ];

            if ($initialTransaction) {

                // UPDATE TRANSAKSI MASUK AWAL
                DB::table('inventory_transactions')
                    ->where('id', $initialTransaction->id)
                    ->update($transactionData);

            } else {

                // BUAT TRANSAKSI AWAL JIKA BELUM ADA
                $transactionData['reference_number'] = (string) Str::uuid();


                DB::table('inventory_transactions')
                    ->insert($transactionData);
            }
        });

        return redirect()
            ->route('stokgudang.index')
            ->with('success', 'Data barang berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $material = DB::table('materials')
            ->where('id', $id)
            ->first();

        if (!$material) {
            abort(404, 'Material tidak ditemukan.');
        }

        $hasTransactions = DB::table('inventory_transactions')
            ->where('material_id', $id)
            ->exists();

        if ($hasTransactions) {
            return redirect()
                ->route('stokgudang.index')
                ->with(
                    'error',
                    'Barang tidak dapat dihapus karena memiliki riwayat transaksi.'
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
