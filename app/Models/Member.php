<?php
namespace App\Models;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;
use Illuminate\Notifications\Notifiable;

class Member extends AuthenticatableUser implements Authenticatable
{
    use HasFactory,  Notifiable;

    protected $fillable = [
        'email',
        'username',
        'password',
        'badge_id',
        'customer_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
}
