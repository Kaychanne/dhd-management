<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterHarga extends Model
{
    use HasFactory;

    protected $table = 'material_price';

    // Tabel material_price tidak menggunakan timestamps
    public $timestamps = false;

    protected $fillable = [
        'material_id',
        'supplier_id',
        'price',
        'price_date',
        'is_reference',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_date' => 'date',
        'is_reference' => 'boolean',
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
