<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    // Halaman Dashboard Inventori
    public function index()
    {
        $categories = Category::all();
        $materials = Material::with(['category', 'brand'])->get();

        return view('inventori', compact('categories', 'materials'));
    }

    // Halaman Tambah Material
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();

        return view('materials.create', compact('categories', 'brands'));
    }
    public function show($id)
    {
        $material = Material::findOrFail($id);
        return view('materials.show', compact('material')); 
        
    }
    // Proses Simpan Data Material
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:materials,code',
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'series' => 'required',
            'specification' => 'required',
            'size' => 'required',
            'color' => 'required',
            'motif' => 'required',
            'finish' => 'required',
            'unit' => 'required',
            'stok' => 'required|numeric',
            'minimum_stock' => 'required|numeric',
            'description' => 'required',
        ]);

        Material::create($validated);

        return redirect()->route('inventori.index')->with('success', 'Material berhasil ditambahkan!');
    }
}
