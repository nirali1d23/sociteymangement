<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class MaintanceProcess extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function staff()
    {
        return $this->belongsTo(User::class);
    }

    public function maintance()
    {
        return $this->belongsTo(maintance::class);
    }
}
