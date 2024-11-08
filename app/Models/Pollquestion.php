<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pollquestion extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function polloption()  
    {  
          return $this->hasMany(Polloptions::class,'question_id');  
 
    } 

    public function pollsurvey()
{
    return $this->hasMany(Pollsurvey::class, 'poll_question_id');
}
}
