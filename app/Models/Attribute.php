<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $table = 'attributes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name','value'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'attribute_product');
    }
}
