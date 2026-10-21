<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';

    // WAJIB: Matikan timestamps otomatis karena tabel tidak punya created_at & updated_at
    public $timestamps = false;

    protected $fillable = [
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
    ];

    public function materialPrices()
    {
        return $this->hasMany(MasterHarga::class, 'supplier_id');
    }
}
?>