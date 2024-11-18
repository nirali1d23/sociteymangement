<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeComment extends Model
{
    use HasFactory;

    protected $table = 'notice_comments';
    protected $guarded = [];

    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function notice()
    {
        return $this->belongsTo(Notice::class, 'notice_id');
    }
}
