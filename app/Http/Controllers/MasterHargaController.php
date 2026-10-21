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
        $prices = MasterHarga::with(['material.category', 'supplier'])
            ->latest('price_date')
            ->get();
        $totalMaterials = Material::count();
        $totalPrices    = MasterHarga::count();
        $materials      = Material::all();
        $suppliers      = Supplier::all();
        return view('masterharga', compact('prices', 'totalMaterials', 'totalPrices', 'materials', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_id'  => 'required|exists:materials,id',
            'supplier_id'  => 'required|exists:supplier,id',
            'price'        => 'required|numeric|min:0',
            'price_date'   => 'required|date',
            'is_reference' => 'nullable|boolean',
            'notes'        => 'nullable|string',
        ]);

        MasterHarga::create([
            'material_id'  => $request->material_id,
            'supplier_id'  => $request->supplier_id,
            'price'        => $request->price,
            'price_date'   => $request->price_date,
            'is_reference' => $request->is_reference ?? 1,
            'notes'        => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Data harga berhasil disimpan!');
    }
}
?>