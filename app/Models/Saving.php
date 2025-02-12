<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Saving extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'description', 'saving_amount', 'total_payout', 'savings_duration', 'total_members', 'status', 'payment_frequency', 'penalty_rate', 'start_date', 'end_date'
    ];

    protected $with = ['payout_turn_method', 'creator', 'members'];
    protected $appends = ['formatted_start_date', 'formatted_end_date', 'created'];



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->savings_uid = self::generateUniqueCode();
        });
    }


    public static function generateUniqueCode($length = 10)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'; // Letters and numbers

        do {
            $code = substr(str_shuffle(str_repeat($characters, $length)), 0, $length); // Generate random string
        } while (self::where('savings_uid', $code)->exists()); // Ensure it's unique

        return $code;
    }

    // public function creator(){
    //     return $this->belongsTo(User::class, 'user_id');
    // }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'saving_user')->withPivot('payout_turn')->withTimestamps();
    }


    public function isMember(User $user)
    {
        return $this->members()->where('user_id', $user->id)->exists();
    }


    public function isCreator(User $user)
    {
        return $this->creator_id === $user->id;
    }

    public function payout_turn_method(){
        return $this->belongsTo(PayoutTurnMethod::class, 'payout_turn_method_id');
    }

    public function getFormattedStartDateAttribute(){
        return Carbon::parse($this->attributes['start_date'])->format('F jS, Y');
    }

    public function getFormattedEndDateAttribute(){
        return Carbon::parse($this->attributes['end_date'])->format('F jS, Y');
    }

    public function getCreatedAttribute(){
        // return $this->created_at->diffForHumans();
        return Carbon::parse($this->created_at)->format('F jS, Y');
    }

    public function membershipRequest(){
        return $this->hasMany(MembershipRequest::class);
    }

    public function invites(){
        return $this->hasMany(Invite::class);
    }

    public function setPaymentFrequency($value)
    {
        $this->attributes['payment_frequency'] = ucfirst(strtolower($value));
    }

    // public function isCreator(){
    //     $auth = auth()->id();
    //     return $this->creator_id == $auth;
    // }

    // public function isMember(){
    //     $auth = auth()->id();
    //     return $this->members()->where('user_id', $auth)->exists();
    // }
}
