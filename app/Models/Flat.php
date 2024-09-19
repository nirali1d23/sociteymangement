<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function allotment()
    {
        return $this->belongsToMany(Allotment::class,'flat_id');
    }

    public function houses()
    {
        return $this->hasMany(House::class);
    }

    

}
