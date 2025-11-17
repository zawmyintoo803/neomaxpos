<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'contact_person',
        'address',
        'supplier_type_id',
        'division_id',
        'township_id',
    ];

    public function supplierType()
    {
        return $this->belongsTo(SupplierType::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function township()
    {
        return $this->belongsTo(Township::class);
    }

}
