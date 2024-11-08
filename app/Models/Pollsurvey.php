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
    public function pollquestion()
{
    return $this->belongsTo(Pollquestion::class, 'poll_question_id'); // adjust foreign key if necessary
}

    public function user()
{
    return $this->belongsTo(User::class);
}

}
