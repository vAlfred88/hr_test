<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    public function properties() {
        return $this->hasMany(Property::class);
    }
    
    /**
     * Scope a query to filter products by their properties.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $properties
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterByProperties(Builder $query, array $properties): Builder
    {
        // Iterate over each property filter provided in the request
        foreach ($properties as $key => $values) {
            // Ensure values are passed as an array
            if (is_array($values)) {
                $query->whereHas('properties', function ($q) use ($key, $values) {
                    $q->where('name', $key)
                    ->whereIn('value', $values);
                });
            }
        }
        
        return $query;
    }
}
