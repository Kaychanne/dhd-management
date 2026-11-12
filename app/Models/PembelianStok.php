<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianStok extends Model
{
    protected $table = 'inventory_transactions';

    protected $primaryKey = 'id';

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
        'created_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}

