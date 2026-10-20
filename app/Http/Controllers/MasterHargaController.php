<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MasterHargaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN UTAMA
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'harga');
        $search = trim($request->get('search', ''));

        /*
        |--------------------------------------------------------------------------
        | MATERIAL
        |--------------------------------------------------------------------------
        */
        $materials = DB::table('materials')
            ->orderBy('id', 'desc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SUPPLIER
        |--------------------------------------------------------------------------
        */
        $suppliers = DB::table('supplier')
            ->orderBy('id', 'desc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | MASTER HARGA
        |--------------------------------------------------------------------------
        |
        | Asumsi tabel material_price memiliki:
        | id
        | material_id
        | supplier_id
        | price / harga
        | status
        |
        */

        $materialPrices = DB::table('material_price')
            ->leftJoin(
                'materials',
                'material_price.material_id',
                '=',
                'materials.id'
            )
            ->leftJoin(
                'supplier',
                'material_price.supplier_id',
                '=',
                'supplier.id'
            )
            ->select(
                'material_price.*',
                'materials.name as material_name',
                'materials.code as material_code',
                'supplier.name as supplier_name'
            )
            ->orderBy('material_price.id', 'desc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $searchLower = strtolower($search);

            $materialPrices = $materialPrices->filter(function ($item) use ($searchLower) {

                return str_contains(
                    strtolower((string) ($item->material_name ?? '')),
                    $searchLower
                )
                ||
                str_contains(
                    strtolower((string) ($item->material_code ?? '')),
                    $searchLower
                )
                ||
                str_contains(
                    strtolower((string) ($item->supplier_name ?? '')),
                    $searchLower
                );
            })->values();

            $suppliers = $suppliers->filter(function ($item) use ($searchLower) {

                return str_contains(
                    strtolower((string) ($item->name ?? '')),
                    $searchLower
                )
                ||
                str_contains(
                    strtolower((string) ($item->email ?? '')),
                    $searchLower
                )
                ||
                str_contains(
                    strtolower((string) ($item->phone ?? '')),
                    $searchLower
                );
            })->values();
        }

        /*
        |--------------------------------------------------------------------------
        | STATISTIC
        |--------------------------------------------------------------------------
        */

        $totalMaterial = $materials->count();

        $totalSupplier = $suppliers->count();

        $totalHarga = $materialPrices->count();

        $supplierAktif = $suppliers->filter(function ($supplier) {

            return !isset($supplier->status)
                || strtolower($supplier->status) === 'aktif'
                || strtolower($supplier->status) === 'active';

        })->count();

        return view('master-harga-supplier', compact(
            'materials',
            'suppliers',
            'materialPrices',
            'totalMaterial',
            'totalSupplier',
            'totalHarga',
            'supplierAktif',
            'tab',
            'search'
        ));
    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH SUPPLIER
    |--------------------------------------------------------------------------
    */

    public function storeSupplier(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'nullable|string|max:50',
        ]);

        DB::table('supplier')->insert([
            'name' => $validated['name'],
            'contact_person' => $validated['contact_person'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => $validated['status'] ?? 'Aktif',
        ]);

        return redirect()
            ->route('master-harga-supplier.index', ['tab' => 'supplier'])
            ->with('success', 'Supplier berhasil ditambahkan.');
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE SUPPLIER
    |--------------------------------------------------------------------------
    */

    public function updateSupplier(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'nullable|string|max:50',
        ]);

        DB::table('supplier')
            ->where('id', $id)
            ->update([
                'name' => $validated['name'],
                'contact_person' => $validated['contact_person'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'] ?? null,
                'status' => $validated['status'] ?? 'Aktif',
            ]);

        return redirect()
            ->route('master-harga-supplier.index', ['tab' => 'supplier'])
            ->with('success', 'Supplier berhasil diperbarui.');
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE SUPPLIER
    |--------------------------------------------------------------------------
    */

    public function destroySupplier($id)
    {
        DB::table('supplier')
            ->where('id', $id)
            ->delete();

        return redirect()
            ->route('master-harga-supplier.index', ['tab' => 'supplier'])
            ->with('success', 'Supplier berhasil dihapus.');
    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH HARGA
    |--------------------------------------------------------------------------
    */

    public function storePrice(Request $request)
    {
        $validated = $request->validate([
            'material_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'status' => 'nullable|string|max:50',
        ]);

        DB::table('material_price')->insert([
            'material_id' => $validated['material_id'],
            'supplier_id' => $validated['supplier_id'],
            'price' => $validated['price'],
            'status' => $validated['status'] ?? 'Aktif',
        ]);

        return redirect()
            ->route('master-harga-supplier.index', ['tab' => 'harga'])
            ->with('success', 'Harga material berhasil ditambahkan.');
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE HARGA
    |--------------------------------------------------------------------------
    */

    public function updatePrice(Request $request, $id)
    {
        $validated = $request->validate([
            'material_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'price' => 'required|numeric|min:0',
            'status' => 'nullable|string|max:50',
        ]);

        DB::table('material_price')
            ->where('id', $id)
            ->update([
                'material_id' => $validated['material_id'],
                'supplier_id' => $validated['supplier_id'],
                'price' => $validated['price'],
                'status' => $validated['status'] ?? 'Aktif',
            ]);

        return redirect()
            ->route('master-harga-supplier.index', ['tab' => 'harga'])
            ->with('success', 'Harga material berhasil diperbarui.');
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE HARGA
    |--------------------------------------------------------------------------
    */

    public function destroyPrice($id)
    {
        DB::table('material_price')
            ->where('id', $id)
            ->delete();

        return redirect()
            ->route('master-harga-supplier.index', ['tab' => 'harga'])
            ->with('success', 'Harga material berhasil dihapus.');
    }
}