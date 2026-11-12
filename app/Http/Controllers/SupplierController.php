<?php
namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    public function index()
    {
        $suppliers = Supplier::withCount('materialPrices')
            ->orderBy('id', 'desc')
            ->get();

        return view('supplier', compact('suppliers'));
    }


    public function create()
    {
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string|max:255',
        ]);

        Supplier::create($validated);

        return redirect()
            ->route('supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);

        return view('supplier.edit', compact('supplier'));
    }

    // UPDATE DATA SUPPLIER
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string|max:255',
        ]);

        $supplier->update($validated);

        return redirect()
            ->route('supplier.index')
            ->with('success', 'Data supplier berhasil diperbarui!');
    }

    // HAPUS SUPPLIER
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        // Cek apakah supplier masih digunakan
        if ($supplier->materialPrices()->exists()) {
            return redirect()
                ->route('supplier.index')
                ->with(
                    'error',
                    'Supplier tidak dapat dihapus karena masih digunakan pada data harga material.'
                );
        }

        $supplier->delete();

        return redirect()
            ->route('supplier.index')
            ->with('success', 'Supplier berhasil dihapus!');
    }
}
