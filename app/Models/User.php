<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name', 'phone', 'profile_pic', 'post_code', 'first_line_address', 'acct_no', 'sort_code',
        'email',
        'password',
    ];

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    protected $appends = ['initials', 'fullname'];


    protected static function boot() {
        parent::boot();
    
        static::creating(function ($user) {
            $user->uuid = Str::uuid();
        });

        // static::addGlobalScope('order', function (Builder $builder) {
        //     $builder->orderBy('created_at', 'desc');
        // });
    }

    // override default notification for email verification
    public function sendEmailVerificationNotification() {
        $this->notify(new CustomVerifyEmail);
    }

    public function getFullnameAttribute(){
        return $this->first_name.' '.$this->last_name;
    }

    public function setFirstNameAttribute($value){
        $this->attributes['first_name'] = ucfirst(strtolower($value));
    }
    
    public function setLastNameAttribute($value){
        $this->attributes['last_name'] = ucfirst(strtolower($value));
    }

     // User is the creator of savings groups
     public function createdSavings()
     {
         return $this->hasMany(Saving::class, 'creator_id');
     }


     public function savings()
    {
        return $this->belongsToMany(Saving::class, 'saving_user')->withPivot('payout_turn')
        ->withTimestamps();
    }


    public function isCreatorOfSaving($savingId)
    {
        return $this->createdSavings()->where('id', $savingId)->exists();
    }


    public function isMemberOfSaving($savingId)
    {
        return $this->savings()->wherePivot('saving_id', $savingId)->exists();
    }

    public function invites(){
        return $this->hasMany(Invite::class);
    }
    

    public function getInitialsAttribute(){
        $firstname = $this->first_name;
        $lastname = $this->last_name;
        $fni = substr($firstname, 0,1);
        $lni = substr($lastname, 0,1);
        return $fni.''.$lni;
    }

    public function membershipRequest(){
        return $this->hasOne(MembershipRequest::class);
    }

}
