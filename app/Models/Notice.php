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
    public function toArray()
    {
        $attributes = parent::toArray();

        // Ensure created_at and updated_at are in Asia/Kolkata timezone
        $attributes['created_at'] = Carbon::parse($this->created_at)->setTimezone('Asia/Kolkata')->toDateTimeString();
        $attributes['updated_at'] = Carbon::parse($this->updated_at)->setTimezone('Asia/Kolkata')->toDateTimeString();

        return $attributes;
    }
}
