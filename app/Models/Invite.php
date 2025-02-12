<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invite extends Model
{
    use HasFactory;

    protected $appends = ['created', 'accepted'];

    protected $fillable = ['user_id', 'saving_id', 'invite_token', 'status', 'sent_at', 'accepted_at', 'invite_method'];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->invite_token = self::generateUniqueCode();
    //     });
    // }

    // public static function generateUniqueCode($length = 8)
    // {
    //     $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    //     do {
    //         $code = substr(str_shuffle(str_repeat($characters, $length)), 0, $length); // Generate random string
    //     } while (self::where('savings_uid', $code)->exists()); 

    //     return $code;
    // }

    public function savingGroup(){
        return $this->belongsTo(Saving::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getCreatedAttribute(){
        return Carbon::parse($this->created_at)->format('jS F, Y');
    }
    public function getAcceptedAttribute(){
        return Carbon::parse($this->accepted_at)->format('jS F, Y');
    }
}
