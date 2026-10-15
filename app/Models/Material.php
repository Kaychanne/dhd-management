<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi ke Model Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Relasi ke Model Brand (Pastikan fungsi ini ada)
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}