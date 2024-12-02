<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;

    protected $guarded = [];

    // public function flat()
    // {
    //     return $this->belongsTo(Flat::class);
    // }

    public function block()
    {
        return $this->belongsTo(Flat::class, 'flat_id');
    }
    


}
