<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'country_id', 'status'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'country_id' => 'integer',
    ];

    /**
     * Get the country that the customer belongs to.
     */
    public function country()
    {
        return $this->belongsTo(Port::class, 'country_id');
    }

    /**
     * Get the inquiries for the customer.
     */
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'customer_id', 'id');
    }

    /**
     * Get the submitted inquiries for the customer.
     */
    public function submittedInquiries()
    {
        return $this->inquiries()->whereNotNull('created_at');
    }

    /**
     * Get the purchased vehicles (inquiries with payment received status).
     */
    public function purchases()
    {
        return $this->inquiries()->where('vehicle_status', 'Payment Received');
    }
}