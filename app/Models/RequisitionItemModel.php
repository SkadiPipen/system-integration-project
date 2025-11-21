<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionItemModel extends Model
{
    use HasFactory;

    protected $table = 'requisition_items';
    protected $primaryKey = 'req_item_id';
    public $timestamps = true; // Change this to true since your migration has timestamps

    protected $fillable = [
        'req_id', 'prod_id', 'quantity', 'unit', 'remarks' // Remove 'qty' and 'status'
    ];

    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'prod_id', 'prod_id');
    }

    public function requisition()
    {
        return $this->belongsTo(RequisitionModel::class, 'req_id', 'req_id');
    }
}