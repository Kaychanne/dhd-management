<?php

namespace App\Http\Controllers;

use App\Models\MasterHarga;
use App\Models\Material;
use App\Models\Supplier; // Sesuaikan jika menggunakan tabel suppliers
use Illuminate\Http\Request;

class MasterHargaController extends Controller
{
    public function index()
    {
        // Ambil data harga beserta relasi material dan kategori
        $prices = MasterHarga::with(['material.category', 'supplier'])
            ->latest('price_date')
            ->get()
            ->map(function ($item) {
                return (object) [
                    'id' => $item->id,
                    'material_code' => $item->material->code ?? 'MAT-' . str_pad($item->material_id, 3, '0', STR_PAD_LEFT),
                    'material_name' => $item->material->name ?? '-',
                    'category_name' => $item->material->category->name ?? '-',
                    'price' => $item->price,
                    'unit' => $item->material->unit ?? 'pcs',
                    'status' => $item->is_reference ? 'active' : 'inactive',
                    'price_date' => $item->price_date,
                    'notes' => $item->notes,
                ];
            });

        $totalMaterials = Material::count();
        $totalPrices = MasterHarga::count();
        $materials = Material::all();
        $suppliers = class_exists(Supplier::class) ? Supplier::all() : [];

        return view('masterharga', compact('prices', 'totalMaterials', 'totalPrices', 'materials', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_id'  => 'required|exists:materials,id',
            'supplier_id'  => 'nullable|integer',
            'price'        => 'required|numeric|min:0',
            'price_date'   => 'required|date',
            'is_reference' => 'nullable|boolean',
            'notes'        => 'nullable|string',
        ]);

        MasterHarga::create([
            'material_id'  => $request->material_id,
            'supplier_id'  => $request->supplier_id ?? 1, // Default supplier ID jika kosong
            'price'        => $request->price,
            'price_date'   => $request->price_date,
            'is_reference' => $request->has('is_reference') ? 1 : 0,
            'notes'        => $request->notes ?? '',
        ]);

        return redirect()->back()->with('success', 'Harga material berhasil disimpan!');
    }

    public function destroy($id)
    {
        $price = MasterHarga::findOrFail($id);
        $price->delete();

        return redirect()->back()->with('success', 'Data harga berhasil dihapus!');
    }
}
