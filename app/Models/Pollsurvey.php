<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pollsurvey extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function polloptions()
    {
        return $this->belongsTo(Polloptions::class,'poll_option_id'); 
        
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

}
