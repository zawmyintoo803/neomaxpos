<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
    'customer_code','customer_type','name','phone','email','division_id','member_card_id','township_id','address',
    'customer_type_id','points','remarks'
];


    public function division() {
        return $this->belongsTo(Division::class);
    }

    public function township() {
        return $this->belongsTo(Township::class);
    }

    public function customerType() {
        return $this->belongsTo(CustomerType::class);
    }
}
