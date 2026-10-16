<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        $materials = Material::with(['category', 'brand'])->get();

        return view('inventori', compact('categories', 'materials'));
    }


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

    public function edit($id)
    {
        $material = Material::findOrFail($id);
        $categories = Category::all();
        $brands = Brand::all();

        return view('materials.edit', compact('material', 'categories', 'brands'));
    }


    public function update(Request $request, $id)
    {
        $material = Material::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|unique:materials,code,' . $id,
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required',
            'series' => 'required',
            'specification' => 'required',
            'size' => 'required',
            'color' => 'required',
            'motif' => 'required',
            'finish' => 'required',
            'unit' => 'required',
            'stok' => 'required|integer|min:0',
            'minimum_stock' => 'required|numeric',
            'description' => 'required',
        ]);

        $material->update($validated);

        return redirect()->route('inventori.index')->with('success', 'Data material berhasil diperbarui!');
    }


    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        $material->delete();

        return redirect()->route('inventori.index')->with('success', 'Material berhasil dihapus!');
    }
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
