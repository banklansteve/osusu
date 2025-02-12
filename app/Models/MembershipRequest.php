<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MembershipRequest extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_id', 'saving_id', 'status'
     ];

    //  protected $with = ['savings', 'user'];

    protected $appends = ['created'];


     public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function savings()
    {
        return $this->belongsTo(Saving::class, 'saving_id');
    }

    public function getCreatedAttribute(){
        return Carbon::parse($this->created_at)->format('jS F, Y');
    }
}
