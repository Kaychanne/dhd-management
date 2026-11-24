<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->date('purchase_date');
            $table->string('invoice_number', 100)->unique();
            // Tidak memakai foreign key agar kompatibel dengan tabel supplier lama.
            $table->unsignedBigInteger('supplier_id')->nullable()->index();
            $table->string('project_name', 150)->nullable()->index();
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->decimal('goods_total', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->string('status', 30)->default('Tercatat');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id')->index();
            $table->unsignedBigInteger('material_id')->nullable()->index();
            $table->string('material_name', 255);
            $table->string('unit', 50)->nullable();
            $table->decimal('quantity', 15, 2);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('shipping_allocated', 15, 2)->default(0);
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->timestamps();
        });

        // inventory_transactions tetap menjadi ledger stok. purchase_id menghubungkan
        // setiap barang masuk dengan satu invoice pembelian.
        if (Schema::hasTable('inventory_transactions')
            && !Schema::hasColumn('inventory_transactions', 'purchase_id')) {
            Schema::table('inventory_transactions', function (Blueprint $table) {
                $table->unsignedBigInteger('purchase_id')->nullable()->index()->after('id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('inventory_transactions')
            && Schema::hasColumn('inventory_transactions', 'purchase_id')) {
            Schema::table('inventory_transactions', function (Blueprint $table) {
                $table->dropIndex(['purchase_id']);
                $table->dropColumn('purchase_id');
            });
        }

        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');
    }
};
