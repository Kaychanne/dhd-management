<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
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
        $suppliers = DB::table('supplier')
            ->orderBy('name')
            ->get();

        $purchases = Purchase::with([
            'supplier',
            'items.material',
        ])
            ->latest('purchase_date')
            ->latest('id')
            ->get()
            ->map(function ($purchase) {
                $purchase->total_quantity = $purchase->items->sum('quantity');
                return $purchase;
            });

        $totalIncoming = $purchases->sum('total_quantity');
        $totalPurchases = $purchases->count();
        $totalShipping = $purchases->sum('shipping_cost');

        return view('pembelianstok', compact(
            'suppliers',
            'purchases',
            'totalIncoming',
            'totalPurchases',
            'totalShipping'
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

        $materials = DB::table('materials')
            ->orderBy('name')
            ->get();

        $suppliers = DB::table('supplier')
            ->orderBy('name')
            ->get();

        return view('pembelianstok.create', compact(
            'prices',
            'materials',
            'suppliers'
        ));
    }

    /**
     * Menyimpan pembelian baru beserta barangnya.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'purchase_date'       => ['required', 'date'],
            'invoice_number'      => ['nullable', 'string', 'max:100'],
            'supplier_id'         => ['required', 'integer', 'exists:supplier,id'],
            'project_name'        => ['nullable', 'string', 'max:150'],
            'shipping_cost'       => ['nullable', 'numeric', 'min:0'],
            'status'              => ['nullable', 'string', 'max:30'],
            'notes'               => ['nullable', 'string'],
            'items'               => ['required', 'array', 'min:1'],
            'items.*.source'      => ['nullable', 'in:master,manual'],
            'items.*.price_mode'  => ['nullable', 'in:master,manual'],
            'items.*.price_id'    => ['nullable', 'integer', 'exists:material_price,id'],
            'items.*.material_id' => ['nullable', 'integer', 'exists:materials,id'],
            'items.*.price'       => ['nullable', 'numeric', 'min:0'],
            'items.*.quantity'    => ['required', 'numeric', 'gt:0'],
            'items.*.unit'        => ['nullable', 'string', 'max:50'],
        ]);

        if (!Auth::check()) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        $items = $data['items'];
        $shipping = (float) ($data['shipping_cost'] ?? 0);

        $masterPrices = DB::table('material_price')
            ->whereIn('id', collect($items)->pluck('price_id')->filter()->unique())
            ->get()
            ->keyBy('id');

        $materialIds = collect($items)
            ->pluck('material_id')
            ->filter()
            ->merge($masterPrices->pluck('material_id')->filter())
            ->unique();

        $materials = DB::table('materials')
            ->whereIn('id', $materialIds)
            ->get()
            ->keyBy('id');

        $resolvedItems = [];
        $totalQuantity = 0.0;
        $goodsTotal = 0.0;

        foreach ($items as $index => $item) {
            $source = $item['source'] ?? ($item['price_mode'] ?? 'manual');
            $materialId = null;
            $unitPrice = 0.0;

            if ($source === 'master') {
                $masterPrice = $masterPrices->get($item['price_id'] ?? null);

                if (!$masterPrice) {
                    throw ValidationException::withMessages([
                        "items.$index.price_id" => 'Master harga tidak ditemukan.',
                    ]);
                }

                $materialId = (int) $masterPrice->material_id;
                $unitPrice = (float) $masterPrice->price;
            } else {
                $materialId = isset($item['material_id']) && $item['material_id'] !== ''
                    ? (int) $item['material_id']
                    : null;

                if (!$materialId) {
                    throw ValidationException::withMessages([
                        "items.$index.material_id" => 'Pilih material yang sudah terdaftar.',
                    ]);
                }

                $unitPrice = (float) ($item['price'] ?? 0);
            }

            $material = $materials->get($materialId);
            $quantity = (float) $item['quantity'];
            $subtotal = round($unitPrice * $quantity, 2);
            $unit = !empty($item['unit']) ? $item['unit'] : ($material->unit ?? '');

            $totalQuantity += $quantity;
            $goodsTotal += $subtotal;

            $resolvedItems[] = [
                'material_id'   => $materialId,
                'material_name' => $material->name ?? ('Material #' . $materialId),
                'unit'          => $unit,
                'quantity'      => $quantity,
                'unit_price'    => $unitPrice,
                'subtotal'      => $subtotal,
            ];
        }

        $goodsTotal = round($goodsTotal, 2);
        $grandTotal = round($goodsTotal + $shipping, 2);

        $invoice = !empty($data['invoice_number'])
            ? $data['invoice_number']
            : 'PB-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));

        DB::transaction(function () use (
            $data,
            $resolvedItems,
            $shipping,
            $totalQuantity,
            $goodsTotal,
            $grandTotal,
            $invoice
        ) {
            $purchase = Purchase::create([
                'purchase_date'  => $data['purchase_date'],
                'invoice_number' => $invoice,
                'supplier_id'    => $data['supplier_id'],
                'project_name'   => $data['project_name'] ?? null,
                'shipping_cost'  => $shipping,
                'goods_total'    => $goodsTotal,
                'grand_total'    => $grandTotal,
                'status'         => $data['status'] ?? 'Tercatat',
                'notes'          => $data['notes'] ?? null,
                'created_by'     => Auth::id(),
            ]);

            foreach ($resolvedItems as $item) {
                $shippingAllocated = $totalQuantity > 0
                    ? round(($item['quantity'] / $totalQuantity) * $shipping, 2)
                    : 0.0;
                $totalCost = round($item['subtotal'] + $shippingAllocated, 2);
                $unitCost = $item['quantity'] > 0
                    ? round($totalCost / $item['quantity'], 2)
                    : $item['unit_price'];

                PurchaseItem::create([
                    'purchase_id'        => $purchase->id,
                    'material_id'        => $item['material_id'],
                    'material_name'      => $item['material_name'],
                    'unit'               => $item['unit'],
                    'quantity'           => $item['quantity'],
                    'unit_price'         => $item['unit_price'],
                    'subtotal'           => $item['subtotal'],
                    'shipping_allocated' => $shippingAllocated,
                    'unit_cost'          => $unitCost,
                    'total_cost'         => $totalCost,
                ]);

                DB::table('inventory_transactions')->insert([
                    'purchase_id'      => $purchase->id,
                    'material_id'      => $item['material_id'],
                    'supplier_id'      => $data['supplier_id'],
                    'type'             => 'in',
                    'quantity'         => $item['quantity'],
                    'unit_price'       => $unitCost,
                    'total_price'      => $totalCost,
                    'transaction_date' => $data['purchase_date'],
                    'reference_number' => $invoice,
                    'notes'            => $data['notes'] ?? '',
                    'created_by'       => Auth::id(),
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
        $purchase = Purchase::with('items')->find($id);

        if (!$purchase) {
            return redirect()
                ->route('pembelianstok.index')
                ->with('error', 'Transaksi pembelian tidak ditemukan.');
        }

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

        $materials = DB::table('materials')
            ->orderBy('name')
            ->get();

        $suppliers = DB::table('supplier')
            ->orderBy('name')
            ->get();

        $firstItem = $purchase->items->first();
        $masterPrice = $firstItem
            ? DB::table('material_price')->where('material_id', $firstItem->material_id)->first()
            : null;

        $purchase->price_mode = $masterPrice ? 'master' : 'manual';
        $purchase->price_id = $masterPrice ? $masterPrice->id : null;
        $purchase->price = $firstItem ? (float) $firstItem->unit_price : 0;
        $purchase->shipping_cost = round((float) $purchase->shipping_cost, 0);
        $purchase->notes = $purchase->notes ?? '';

        return view('pembelianstok.edit', compact(
            'purchase',
            'prices',
            'materials',
            'suppliers'
        ));
    }

    /**
     * Memperbarui transaksi pembelian.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'purchase_date'       => ['required', 'date'],
            'invoice_number'      => ['nullable', 'string', 'max:100'],
            'supplier_id'         => ['required', 'integer', 'exists:supplier,id'],
            'project_name'        => ['nullable', 'string', 'max:150'],
            'shipping_cost'       => ['nullable', 'numeric', 'min:0'],
            'status'              => ['nullable', 'string', 'max:30'],
            'notes'               => ['nullable', 'string'],
            'items'               => ['required', 'array', 'min:1'],
            'items.*.source'      => ['nullable', 'in:master,manual'],
            'items.*.price_mode'  => ['nullable', 'in:master,manual'],
            'items.*.price_id'    => ['nullable', 'integer', 'exists:material_price,id'],
            'items.*.material_id' => ['nullable', 'integer', 'exists:materials,id'],
            'items.*.price'       => ['nullable', 'numeric', 'min:0'],
            'items.*.quantity'    => ['required', 'numeric', 'gt:0'],
            'items.*.unit'        => ['nullable', 'string', 'max:50'],
        ]);

        if (!Auth::check()) {
            abort(403, 'Silakan login terlebih dahulu.');
        }

        $purchase = Purchase::with('items')->find($id);

        if (!$purchase) {
            return redirect()
                ->route('pembelianstok.index')
                ->with('error', 'Transaksi tidak ditemukan.');
        }

        $items = $data['items'];
        $shipping = (float) ($data['shipping_cost'] ?? 0);

        $masterPrices = DB::table('material_price')
            ->whereIn('id', collect($items)->pluck('price_id')->filter()->unique())
            ->get()
            ->keyBy('id');

        $materialIds = collect($items)
            ->pluck('material_id')
            ->filter()
            ->merge($masterPrices->pluck('material_id')->filter())
            ->unique();

        $materials = DB::table('materials')
            ->whereIn('id', $materialIds)
            ->get()
            ->keyBy('id');

        $resolvedItems = [];
        $totalQuantity = 0.0;
        $goodsTotal = 0.0;

        foreach ($items as $index => $item) {
            $source = $item['source'] ?? ($item['price_mode'] ?? 'manual');
            $materialId = null;
            $unitPrice = 0.0;

            if ($source === 'master') {
                $masterPrice = $masterPrices->get($item['price_id'] ?? null);

                if (!$masterPrice) {
                    throw ValidationException::withMessages([
                        "items.$index.price_id" => 'Master harga tidak ditemukan.',
                    ]);
                }

                $materialId = (int) $masterPrice->material_id;
                $unitPrice = (float) $masterPrice->price;
            } else {
                $materialId = isset($item['material_id']) && $item['material_id'] !== ''
                    ? (int) $item['material_id']
                    : null;

                if (!$materialId) {
                    throw ValidationException::withMessages([
                        "items.$index.material_id" => 'Pilih material yang sudah terdaftar.',
                    ]);
                }

                $unitPrice = (float) ($item['price'] ?? 0);
            }

            $material = $materials->get($materialId);
            $quantity = (float) $item['quantity'];
            $subtotal = round($unitPrice * $quantity, 2);
            $unit = !empty($item['unit']) ? $item['unit'] : ($material->unit ?? '');

            $totalQuantity += $quantity;
            $goodsTotal += $subtotal;

            $resolvedItems[] = [
                'material_id'   => $materialId,
                'material_name' => $material->name ?? ('Material #' . $materialId),
                'unit'          => $unit,
                'quantity'      => $quantity,
                'unit_price'    => $unitPrice,
                'subtotal'      => $subtotal,
            ];
        }

        $goodsTotal = round($goodsTotal, 2);
        $grandTotal = round($goodsTotal + $shipping, 2);
        $invoice = !empty($data['invoice_number'])
            ? $data['invoice_number']
            : $purchase->invoice_number;

        DB::transaction(function () use (
            $purchase,
            $data,
            $resolvedItems,
            $shipping,
            $totalQuantity,
            $goodsTotal,
            $grandTotal,
            $invoice
        ) {
            $purchase->update([
                'purchase_date'  => $data['purchase_date'],
                'invoice_number' => $invoice,
                'supplier_id'    => $data['supplier_id'],
                'project_name'   => $data['project_name'] ?? $purchase->project_name,
                'shipping_cost'  => $shipping,
                'goods_total'    => $goodsTotal,
                'grand_total'    => $grandTotal,
                'status'         => $data['status'] ?? $purchase->status,
                'notes'          => $data['notes'] ?? null,
            ]);

            PurchaseItem::where('purchase_id', $purchase->id)->delete();
            DB::table('inventory_transactions')->where('purchase_id', $purchase->id)->delete();

            foreach ($resolvedItems as $item) {
                $shippingAllocated = $totalQuantity > 0
                    ? round(($item['quantity'] / $totalQuantity) * $shipping, 2)
                    : 0.0;
                $totalCost = round($item['subtotal'] + $shippingAllocated, 2);
                $unitCost = $item['quantity'] > 0
                    ? round($totalCost / $item['quantity'], 2)
                    : $item['unit_price'];

                PurchaseItem::create([
                    'purchase_id'        => $purchase->id,
                    'material_id'        => $item['material_id'],
                    'material_name'      => $item['material_name'],
                    'unit'               => $item['unit'],
                    'quantity'           => $item['quantity'],
                    'unit_price'         => $item['unit_price'],
                    'subtotal'           => $item['subtotal'],
                    'shipping_allocated' => $shippingAllocated,
                    'unit_cost'          => $unitCost,
                    'total_cost'         => $totalCost,
                ]);

                DB::table('inventory_transactions')->insert([
                    'purchase_id'      => $purchase->id,
                    'material_id'      => $item['material_id'],
                    'supplier_id'      => $data['supplier_id'],
                    'type'             => 'in',
                    'quantity'         => $item['quantity'],
                    'unit_price'       => $unitCost,
                    'total_price'      => $totalCost,
                    'transaction_date' => $data['purchase_date'],
                    'reference_number' => $invoice,
                    'notes'            => $data['notes'] ?? '',
                    'created_by'       => Auth::id(),
                ]);
            }
        });

        return redirect()
            ->route('pembelianstok.index')
            ->with('success', 'Transaksi pembelian berhasil diperbarui.');
    }

    /**
     * Menghapus transaksi pembelian.
     */
    public function destroy($id)
    {
        $purchase = Purchase::find($id);

        if (!$purchase) {
            return redirect()
                ->route('pembelianstok.index')
                ->with(
                    'error',
                    'Transaksi pembelian tidak ditemukan.'
                );
        }

        DB::transaction(function () use ($purchase) {
            DB::table('inventory_transactions')->where('purchase_id', $purchase->id)->delete();
            PurchaseItem::where('purchase_id', $purchase->id)->delete();
            $purchase->delete();
        });

        return redirect()
            ->route('pembelianstok.index')
            ->with(
                'success',
                'Transaksi pembelian berhasil dihapus.'
            );
    }
}