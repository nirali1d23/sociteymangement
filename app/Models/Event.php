<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $guarded = []; 

    public function eventfeedback()
    {
        
        return $this->hasMany(EventFeedback::class, 'event_id');

       

    }


}
