<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'billing_history';

    protected $fillable = [
        'invoice_id',
        'paid_amount',
        'description',
        'created_by',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'paid_amount' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the invoice that owns the billing history
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the user who created this billing record
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who verified this billing record
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope to get verified billing records
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    /**
     * Scope to get unverified billing records
     */
    public function scopeUnverified($query)
    {
        return $query->whereNull('verified_at');
    }
}
