<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'inquiry_id',
        'created_by',
        'verified_by',
        'verified_at'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the inquiry that owns the invoice
     */
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }

    /**
     * Get the user who created the invoice
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who verified the invoice
     */
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the billing history for this invoice
     */
    public function billingHistory()
    {
        return $this->hasMany(BillingHistory::class);
    }

    /**
     * Get the total paid amount from billing history
     */
    public function getTotalPaidAttribute()
    {
        return $this->billingHistory()->sum('paid_amount');
    }

    /**
     * Get the remaining amount due
     */
    public function getAmountDueAttribute()
    {
        $totalAmount = $this->inquiry->total_price ?? 0;
        return $totalAmount - $this->total_paid;
    }

    /**
     * Check if invoice is fully paid
     */
    public function getIsFullyPaidAttribute()
    {
        return $this->amount_due <= 0;
    }

    /**
     * Get payment status
     */
    public function getPaymentStatusAttribute()
    {
        if ($this->is_fully_paid) {
            return 'Paid';
        } elseif ($this->total_paid > 0) {
            return 'Partially Paid';
        } else {
            return 'Pending';
        }
    }
}

