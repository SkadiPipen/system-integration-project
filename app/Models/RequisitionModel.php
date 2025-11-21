<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionModel extends Model
{
    use HasFactory;

    protected $table = 'requisitions';
    protected $primaryKey = 'req_id';
    public $timestamps = true; // Change to true since you have timestamps

    protected $fillable = [
        'req_num', 'req_by', 'supplier_id', 'request_date', 
        'require_date', 'remarks', 'req_status'
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'require_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'req_by', 'employee_id');
    }

    public function supplier()
    {
        return $this->belongsTo(SupplierModel::class, 'supplier_id', 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(RequisitionItemModel::class, 'req_id', 'req_id');
    }
}