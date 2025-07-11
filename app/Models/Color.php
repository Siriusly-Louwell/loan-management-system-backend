<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Motorcycle;

class Color extends Model
{
    use HasFactory;

    protected $fillable = ['color', 'motorcycle_id'];
    
    public function motorcyle()
    {
        return $this->belongsTo(Motorcyle::class);
    }
}
