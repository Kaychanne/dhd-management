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

        // Diubah menjadi 'supplier' (membaca resources/views/supplier.blade.php)
        return view('supplier', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string',
        ]);

        Supplier::create([
            'name'           => $request->name,
            'contact_person' => $request->contact_person,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'address'        => $request->address,
        ]);

        return redirect()->back()->with('success', 'Supplier berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->back()->with('success', 'Supplier berhasil dihapus!');
    }
}
?>