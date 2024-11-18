<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function notice_commnet()
    {
        return $this->hasMany(NoticeComment::class, 'notice_id');
    }
}
