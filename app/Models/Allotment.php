<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allotment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function flat()  
    {  
          return $this->belongsTo(House::class,'flat_id');  
 
    } 
    
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
