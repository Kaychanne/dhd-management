<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokGudang extends Model
{
    protected $table = 'inventory_transactions';

    public $timestamps = false;

    protected $fillable = [
        'material_id',
        'supplier_id',
        'type',
        'quantity',
        'unit_price',
        'total_price',
        'transaction_date',
        'reference_number',
        'notes',
    ];

    // Relasi ke material
    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    // Relasi ke supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
