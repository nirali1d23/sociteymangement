<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile_no',
        'user_type',
        'fcm_token'
    ];

    public function allotment()
    {
        return $this->hasOne(Allotment::class,'user_id');
    }
    public function eventfeedback()
    {
        return $this->hasMany(EventFeedback::class, 'event_id');
    }

    public function bookamenities()
    {
        return $this->hasMany(Bookamenities::class, 'user_id');
    }    
    public function maintance()
    {
        return $this->hasMany(maintance::class, 'user_id');
    }
  
    public function preapproval()
    {
        return $this->hasMany(preapproval::class, 'user_id');
    }
    public function pollsurvey()
    {
        return $this->hasMany(Pollsurvey::class, 'user_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            // Delete related records for `hasMany` relationships
            $user->eventfeedback()->delete();
            $user->bookamenities()->delete();
            $user->maintance()->delete();
            $user->preapproval()->delete();
            $user->pollsurvey()->delete();

            // Handle `belongsToMany` relationships (detach pivot table data)
            $user->allotment()->detach();
        });
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
