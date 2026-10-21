<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterHarga extends Model
{
    use HasFactory;

    // Menentukan nama tabel di database
    protected $table = 'material_price';

    // Kolom yang dapat diisi
    protected $fillable = [
        'material_id',
        'supplier_id',
        'price',
        'price_date',
        'is_reference',
        'notes',
    ];

    // Relasi ke tabel materials
    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
?>