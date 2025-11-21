<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    use HasFactory;

    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        's_name',
        's_contact',
        's_num',
        's_address',
        's_email'
    ];

    /**
     * Get the requisitions for this supplier.
     */
    public function requisitions()
    {
        return $this->hasMany(RequisitionModel::class, 'supplier_id', 'supplier_id');
    }

    /**
     * Get the purchase orders for this supplier.
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrderModel::class, 'supplier_id', 'supplier_id');
    }

    /**
     * Scope a query to search suppliers by name or contact.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('s_name', 'like', '%' . $search . '%')
                    ->orWhere('s_contact', 'like', '%' . $search . '%')
                    ->orWhere('s_email', 'like', '%' . $search . '%');
    }

    /**
     * Get the supplier's formatted contact information.
     */
    public function getFormattedContactAttribute()
    {
        return $this->s_contact . ' | ' . $this->s_num;
    }

    /**
     * Get the supplier's complete address.
     */
    public function getCompleteAddressAttribute()
    {
        return $this->s_address;
    }

    /**
     * Check if supplier has any requisitions.
     */
    public function hasRequisitions()
    {
        return $this->requisitions()->exists();
    }

    /**
     * Check if supplier has any purchase orders.
     */
    public function hasPurchaseOrders()
    {
        return $this->purchaseOrders()->exists();
    }
}