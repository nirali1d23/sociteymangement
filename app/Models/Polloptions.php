<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Polloptions extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function pollquestion()
    {
        return $this->belongsTo(Pollquestion::class,'question_id'); 
    }

    public function pollsurvey()  
    {  
          return $this->hasMany(Pollsurvey::class,'poll_option_id');  
 
    } 
   
}
