<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderModel extends Model
{
    use HasFactory;

    protected $table = 'purchase_orders';
    protected $primaryKey = 'po_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'po_num',
        'req_id',
        'supplier_id',
        'ordered_by',
        'order_date',
        'po_status'
    ];

    protected $casts = [
        'order_date' => 'date',
    ];

    /**
     * Get the requisition that this PO is based on.
     */
    public function requisition()
    {
        return $this->belongsTo(RequisitionModel::class, 'req_id', 'req_id');
    }

    /**
     * Get the supplier for this purchase order.
     */
    public function supplier()
    {
        return $this->belongsTo(SupplierModel::class, 'supplier_id', 'supplier_id');
    }

    /**
     * Get the employee who created this purchase order.
     */
    public function orderer()
    {
        return $this->belongsTo(Employee::class, 'ordered_by', 'employee_id');
    }

    /**
     * Get the items for this purchase order.
     */
    public function items()
    {
        return $this->hasMany(PurchaseOrderItemModel::class, 'po_id', 'po_id');
    }

    /**
     * Scope a query to only include pending POs.
     */
    public function scopePending($query)
    {
        return $query->where('po_status', 'pending');
    }

    /**
     * Scope a query to only include approved POs.
     */
    public function scopeApproved($query)
    {
        return $query->where('po_status', 'approved');
    }

    /**
     * Scope a query to only include completed POs.
     */
    public function scopeCompleted($query)
    {
        return $query->where('po_status', 'completed');
    }

    /**
     * Scope a query to only include cancelled POs.
     */
    public function scopeCancelled($query)
    {
        return $query->where('po_status', 'cancelled');
    }

    /**
     * Get the total amount for this purchase order.
     */
    public function getTotalAmountAttribute()
    {
        return $this->items->sum('tot_price');
    }

    /**
     * Get the formatted total amount.
     */
    public function getFormattedTotalAmountAttribute()
    {
        return '₱' . number_format($this->total_amount, 2);
    }

    /**
     * Get the total quantity of items in this PO.
     */
    public function getTotalQuantityAttribute()
    {
        return $this->items->sum('qty_ordered');
    }

    /**
     * Check if PO is pending.
     */
    public function isPending()
    {
        return $this->po_status === 'pending';
    }

    /**
     * Check if PO is approved.
     */
    public function isApproved()
    {
        return $this->po_status === 'approved';
    }

    /**
     * Check if PO is completed.
     */
    public function isCompleted()
    {
        return $this->po_status === 'completed';
    }

    /**
     * Check if PO is cancelled.
     */
    public function isCancelled()
    {
        return $this->po_status === 'cancelled';
    }

    /**
     * Mark PO as approved.
     */
    public function markAsApproved()
    {
        $this->update(['po_status' => 'approved']);
    }

    /**
     * Mark PO as completed.
     */
    public function markAsCompleted()
    {
        $this->update(['po_status' => 'completed']);
    }

    /**
     * Mark PO as cancelled.
     */
    public function markAsCancelled()
    {
        $this->update(['po_status' => 'cancelled']);
    }

    /**
     * Check if this PO was created from a requisition.
     */
    public function isFromRequisition()
    {
        return !is_null($this->req_id);
    }
}