<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintancebill extends Model
{
    use HasFactory;

    // In Maintancebill.php
public function maintancebilllists()
{
    return $this->hasMany(Maintancebilllist::class, 'maintance_bill_id');
}

}
