<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintancebilllist extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function flat()
    {
        return $this->belongsTo(House::class, 'flat_id');

    }
    // In Maintancebilllist.php
public function maintancebill()
{
    return $this->belongsTo(Maintancebill::class, 'maintance_bill_id');
}

}
