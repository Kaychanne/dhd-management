<?php
namespace App\Http\Controllers;

use App\Models\MasterHarga;
use App\Models\Material;
use App\Models\Supplier;
use Illuminate\Http\Request;

class MasterHargaController extends Controller
{
    public function index()
    {
        $prices = MasterHarga::with([
            'material.category',
            'material.brand',
            'supplier'
        ])->latest('price_date')->get();

        $totalMaterials = Material::count();
        $totalPrices = MasterHarga::count();

        return view('masterharga', compact(
            'prices',
            'totalMaterials',
            'totalPrices'
        ));
    }

    // HALAMAN TAMBAH HARGA
    public function create()
    {
        $materials = Material::with([
            'category',
            'brand'
        ])->orderBy('name')->get();

        $suppliers = Supplier::orderBy('name')->get();

        return view('masterharga.create', compact(
            'materials',
            'suppliers'
        ));
    }

    // SIMPAN HARGA BARU
    public function store(Request $request)
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'supplier_id' => 'required|exists:supplier,id',
            'price' => 'required|numeric|min:0',
            'price_date' => 'required|date',
            'is_reference' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        MasterHarga::create($validated);

        return redirect()
            ->route('masterharga.index')
            ->with('success', 'Data harga berhasil ditambahkan!');
    }

    // HALAMAN EDIT HARGA
    public function edit($id)
    {
        $price = MasterHarga::with([
            'material.category',
            'material.brand',
            'supplier'
        ])->findOrFail($id);

        $materials = Material::with([
            'category',
            'brand'
        ])->orderBy('name')->get();

        $suppliers = Supplier::orderBy('name')->get();

        return view('masterharga.edit', compact(
            'price',
            'materials',
            'suppliers'
        ));
    }

    // UPDATE HARGA
    public function update(Request $request, $id)
    {
        $price = MasterHarga::findOrFail($id);

        $validated = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'supplier_id' => 'required|exists:supplier,id',
            'price' => 'required|numeric|min:0',
            'price_date' => 'required|date',
            'is_reference' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        $price->update($validated);

        return redirect()
            ->route('masterharga.index')
            ->with('success', 'Data harga berhasil diperbarui!');
    }

    // HAPUS HARGA
    public function destroy($id)
    {
        $price = MasterHarga::findOrFail($id);

        $price->delete();

        return redirect()
            ->route('masterharga.index')
            ->with('success', 'Data harga berhasil dihapus!');
    }
}
?>