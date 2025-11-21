<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'prod_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'prod_name',
        'category',
        'unit',
        'unit_price',
        'supplier_id'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
    ];

    /**
     * Get the supplier for this product.
     */
    public function supplier()
    {
        return $this->belongsTo(SupplierModel::class, 'supplier_id', 'supplier_id');
    }

    /**
     * Get the requisition items for this product.
     */
    public function requisitionItems()
    {
        return $this->hasMany(RequisitionItemModel::class, 'prod_id', 'prod_id');
    }

    /**
     * Scope a query to search products by name or category.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('prod_name', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%');
    }

    /**
     * Scope a query to only include products from a specific category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to only include products from a specific supplier.
     */
    public function scopeBySupplier($query, $supplierId)
    {
        return $query->where('supplier_id', $supplierId);
    }

    /**
     * Get the formatted unit price.
     */
    public function getFormattedUnitPriceAttribute()
    {
        return '₱' . number_format($this->unit_price, 2);
    }
}