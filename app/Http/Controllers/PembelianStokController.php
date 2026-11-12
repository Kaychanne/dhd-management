<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PembelianStokController extends Controller
{
    /**
     * Menampilkan halaman pembelian dan riwayat transaksi.
     */
    public function index()
    {
        // Master harga beserta informasi material.
        $prices = DB::table('material_price')
            ->leftJoin(
                'materials',
                'material_price.material_id',
                '=',
                'materials.id'
            )
            ->select(
                'material_price.*',
                'materials.name as material_name',
                'materials.code as material_code',
                'materials.unit as material_unit'
            )
            ->orderByDesc('material_price.price_date')
            ->get();

        // Daftar supplier.
        $suppliers = DB::table('supplier')->get();

        // Riwayat transaksi barang masuk.
        $purchases = DB::table('inventory_transactions')
            ->leftJoin(
                'materials',
                'inventory_transactions.material_id',
                '=',
                'materials.id'
            )
            ->leftJoin(
                'supplier',
                'inventory_transactions.supplier_id',
                '=',
                'supplier.id'
            )
            ->where('inventory_transactions.type', 'in')
            ->select(
                'inventory_transactions.*',
                'inventory_transactions.transaction_date as purchase_date',
                'inventory_transactions.reference_number as invoice_number',
                'inventory_transactions.total_price as total_cost',
                'inventory_transactions.quantity as total_quantity',
                'materials.name as material_name',
                'materials.code as material_code',
                'supplier.name as supplier_name'
            )
            ->orderByDesc('inventory_transactions.transaction_date')
            ->orderByDesc('inventory_transactions.id')
            ->get();

        // Mengirim data ke halaman pembelianstok.blade.php.
        return view('pembelianstok', compact(
            'prices',
            'suppliers',
            'purchases'
        ));
    }

    /**
     * Menampilkan halaman form tambah pembelian.
     */
    public function create()
    {
        $prices = DB::table('material_price')
            ->join(
                'materials',
                'material_price.material_id',
                '=',
                'materials.id'
            )
            ->select(
                'material_price.*',
                'materials.name as material_name',
                'materials.code as material_code',
                'materials.unit as material_unit'
            )
            ->orderBy('materials.name')
            ->get();

        $suppliers = DB::table('supplier')
            ->orderBy('name')
            ->get();

        return view('pembelianstok.create', compact(
            'prices',
            'suppliers'
        ));
    }

    /**
     * Menyimpan pembelian baru beserta barangnya.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'purchase_date' => ['required', 'date'],
            'invoice_number' => ['nullable', 'string', 'max:255'],
            'supplier_id' => ['required', 'integer', 'exists:supplier,id'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.source' => ['required', 'in:master,manual'],
            'items.*.price_id' => [
                'required_if:items.*.source,master',
                'nullable',
                'integer',
                'exists:material_price,id'
            ],
            'items.*.material_id' => [
                'nullable',
                'integer',
                'exists:materials,id'
            ],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
        ]);

        if (!Auth::check()) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        $items = $data['items'];
        $shipping = (float) ($data['shipping_cost'] ?? 0);

        $totalQuantity = collect($items)->sum(
            fn ($item) => (float) $item['quantity']
        );

        $invoice = $data['invoice_number']
            ?: 'PB-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));

        DB::transaction(function () use (
            $data,
            $items,
            $shipping,
            $totalQuantity,
            $invoice
        ) {
            foreach ($items as $index => $item) {
                $materialId = null;
                $price = 0;

                if ($item['source'] === 'master') {
                    $masterPrice = DB::table('material_price')
                        ->where('id', $item['price_id'])
                        ->first();

                    if (!$masterPrice) {
                        throw ValidationException::withMessages([
                            "items.$index.price_id" => 'Master harga tidak ditemukan.'
                        ]);
                    }

                    $materialId = $masterPrice->material_id;
                    $price = (float) $masterPrice->price;
                } else {
                    $materialId = $item['material_id'] ?? null;

                    if (!$materialId) {
                        throw ValidationException::withMessages([
                            "items.$index.material_id" => 'Pilih material yang sudah terdaftar.'
                        ]);
                    }
                    $price = (float) ($item['price'] ?? 0);
                }

                $quantity = (float) $item['quantity'];
                $shippingPerUnit = $totalQuantity > 0 ? $shipping / $totalQuantity : 0;
                $costPerUnit = round($price + $shippingPerUnit, 2);
                $totalPrice = round($costPerUnit * $quantity, 2);

                DB::table('inventory_transactions')->insert([
                    'material_id' => $materialId,
                    'supplier_id' => $data['supplier_id'],
                    'type' => 'in',
                    'quantity' => $quantity,
                    'unit_price' => $costPerUnit,
                    'total_price' => $totalPrice,
                    'transaction_date' => $data['purchase_date'],
                    'reference_number' => $invoice,
                    'notes' => $data['notes'] ?? '',
                    'created_by' => Auth::id(),
                ]);
            }
        });

        return redirect()
            ->route('pembelianstok.index')
            ->with('success', 'Pembelian berhasil disimpan.');
    }

    /**
     * Menampilkan form edit pembelian.
     */
    public function edit($id)
    {
        // Ambil data transaksi pembelian
        $purchase = DB::table('inventory_transactions')
            ->where('id', $id)
            ->where('type', 'in')
            ->first();

        if (!$purchase) {
            return redirect()
                ->route('pembelianstok.index')
                ->with('error', 'Transaksi pembelian tidak ditemukan.');
        }

        // Ambil daftar master harga
        $prices = DB::table('material_price')
            ->leftJoin('materials', 'material_price.material_id', '=', 'materials.id')
            ->select(
                'material_price.*',
                'materials.name as material_name',
                'materials.code as material_code',
                'materials.unit as material_unit'
            )
            ->orderByDesc('material_price.price_date')
            ->get();

        // Cari master price yang sesuai dengan material_id jika ada
        $masterPrice = DB::table('material_price')
            ->where('material_id', $purchase->material_id)
            ->first();

        // Atur atribut yang dibaca oleh view edit.blade.php
        $purchase->price_mode = $masterPrice ? 'master' : 'manual';
        $purchase->price_id = $masterPrice ? $masterPrice->id : null;
        
        $basePrice = $masterPrice ? (float)$masterPrice->price : (float)$purchase->unit_price;
        $purchase->price = $basePrice;

        
        $totalGoodsPrice = $basePrice * (float)$purchase->quantity;
        $shippingCost = max(0, (float)$purchase->total_price - $totalGoodsPrice);

        // BULATKAN KE ANGKA BULAT (ROUND TO INTEGER)
        $purchase->shipping_cost = round($shippingCost, 0);
        $purchase->notes = $purchase->notes ?? '';
        
        $purchase->items = collect([$purchase]);

        $suppliers = DB::table('supplier')->get();

        return view('pembelianstok.edit', compact('purchase', 'prices', 'suppliers'));
    }

    /**
     * Memperbarui transaksi pembelian.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'purchase_date' => ['required', 'date'],
            'invoice_number' => ['nullable', 'string', 'max:255'],
            'supplier_id' => ['required', 'integer', 'exists:supplier,id'],
            'shipping_cost' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.price_mode' => ['required', 'in:master,manual'],
            'items.*.price_id' => ['nullable', 'integer'],
            'items.*.material_id' => ['nullable', 'integer'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.quantity' => ['required', 'numeric', 'gt:0'],
        ]);

        $purchase = DB::table('inventory_transactions')
            ->where('id', $id)
            ->where('type', 'in')
            ->first();

        if (!$purchase) {
            return redirect()
                ->route('pembelianstok.index')
                ->with('error', 'Transaksi tidak ditemukan.');
        }

        $firstItemKey = array_key_first($data['items']);
        $firstItem = $data['items'][$firstItemKey];
        $materialId = null;

        if ($firstItem['price_mode'] === 'master' && !empty($firstItem['price_id'])) {
            $mp = DB::table('material_price')->where('id', $firstItem['price_id'])->first();
            $materialId = $mp ? $mp->material_id : null;
        } else {
            $materialId = $firstItem['material_id'] ?? null;
        }

        $quantity = (float) $firstItem['quantity'];
        $price = (float) $firstItem['price'];
        $shipping = (float) ($data['shipping_cost'] ?? 0);

        $shippingPerUnit = $quantity > 0 ? $shipping / $quantity : 0;
        $costPerUnit = round($price + $shippingPerUnit, 2);
        $totalPrice = round($costPerUnit * $quantity, 2);

        DB::table('inventory_transactions')
            ->where('id', $id)
            ->update([
                'material_id' => $materialId,
                'supplier_id' => $data['supplier_id'],
                'quantity' => $quantity,
                'unit_price' => $costPerUnit,
                'total_price' => $totalPrice,
                'transaction_date' => $data['purchase_date'],
                'reference_number' => $data['invoice_number'] ?? $purchase->reference_number,
                'notes' => $data['notes'] ?? '',
            ]);

        return redirect()
            ->route('pembelianstok.index')
            ->with('success', 'Transaksi pembelian berhasil diperbarui.');
    }

    /**
     * Menghapus transaksi pembelian.
     */
    public function destroy($id)
    {
        $purchase = DB::table('inventory_transactions')
            ->where('id', $id)
            ->where('type', 'in')
            ->first();

        if (!$purchase) {
            return redirect()
                ->route('pembelianstok.index')
                ->with(
                    'error',
                    'Transaksi pembelian tidak ditemukan.'
                );
        }

        DB::table('inventory_transactions')
            ->where('id', $id)
            ->where('type', 'in')
            ->delete();

        return redirect()
            ->route('pembelianstok.index')
            ->with(
                'success',
                'Transaksi pembelian berhasil dihapus.'
            );
    }
}