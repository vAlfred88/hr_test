<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    
    public $fillable = ['name', 'value', 'product_id'];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
