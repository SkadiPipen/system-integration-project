<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItemModel extends Model
{
    use HasFactory;

    protected $table = 'purchase_order_items';
    protected $primaryKey = 'po_item_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'po_id',
        'prod_id',
        'qty_ordered',
        'unit_price',
        'tot_price'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'tot_price' => 'decimal:2',
    ];

    /**
     * Get the purchase order that owns this item.
     */
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrderModel::class, 'po_id', 'po_id');
    }

    /**
     * Get the product for this item.
     */
    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'prod_id', 'prod_id');
    }

    /**
     * Calculate total price before saving.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->tot_price = $model->qty_ordered * $model->unit_price;
        });
    }

    /**
     * Get the formatted unit price.
     */
    public function getFormattedUnitPriceAttribute()
    {
        return '₱' . number_format($this->unit_price, 2);
    }

    /**
     * Get the formatted total price.
     */
    public function getFormattedTotalPriceAttribute()
    {
        return '₱' . number_format($this->tot_price, 2);
    }

    /**
     * Accessor for quantity (to maintain consistency with other models)
     */
    public function getQuantityAttribute()
    {
        return $this->qty_ordered;
    }

    /**
     * Mutator for quantity (to maintain consistency with other models)
     */
    public function setQuantityAttribute($value)
    {
        $this->attributes['qty_ordered'] = $value;
    }

    /**
     * Accessor for total price (to maintain consistency with other models)
     */
    public function getTotalPriceAttribute()
    {
        return $this->tot_price;
    }

    /**
     * Mutator for total price (to maintain consistency with other models)
     */
    public function setTotalPriceAttribute($value)
    {
        $this->attributes['tot_price'] = $value;
    }
}