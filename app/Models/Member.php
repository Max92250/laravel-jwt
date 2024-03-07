<?php
namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as AuthenticatableUser;
use Illuminate\Notifications\Notifiable;

class Member extends AuthenticatableUser implements Authenticatable
{
    use HasApiTokens, HasFactory,  Notifiable;

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

    public function credits()
    {
        return $this->belongsToMany(Credit::class);
    }
   
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function checkoutDetails()
    {
        return $this->hasMany(CheckoutDetail::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    
    public function creditLogs()
    {
        return $this->hasMany(CreditLog::class);
    }
  /*  public function hasAccessToCategory($category)
    {
        // Get the ID of the category if it's an object
        $categoryId = $category instanceof Category ? $category->id : $category;
    
        // Retrieve the category IDs associated with the member's customer
        $customerCategoryIds = $this->customer->category()->pluck('id')->toArray();
        
        // Check if the specified category ID is among the associated category IDs
        return in_array($categoryId, $customerCategoryIds);
    }
    */
    

}
