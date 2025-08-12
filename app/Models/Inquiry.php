<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Inquiry extends Model
{
    use HasFactory;

    protected $table = 'inquiry';

    protected $fillable = [
        'user_id',
        'vehicle_name',
        'fob_price',
        'freight_fee',
        'insurance_fee',
        'inspection_fee',
        'discount',
        'inqu_port',
        'total_price',
        'site_url',
        'inqu_name',
        'inqu_email',
        'inqu_mobile',
        'inqu_country',
        'inqu_address',
        'inqu_city',
        'stock_no',
        'vehicle_id',
        'inqu_comment',
        'sales_agent',
        'customer_id',
        'vehicle_status',
        'reserved_expiry_date',
        'final_country',
        'port_name',
        'type_of_purchase',
        'insurance',
        'inspection',
        'status'
    ];

    protected $casts = [
        'reserved_expiry_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'fob_price' => 'decimal:2',
        'freight_fee' => 'decimal:2',
        'insurance_fee' => 'decimal:2',
        'inspection_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    // Vehicle Status Constants
    public const STATUS_RESERVED = 'Reserved';
    public const STATUS_READY_TO_SHIP = 'Ready to Ship';
    public const STATUS_OPEN = 'Open';
    public const STATUS_INACTIVE = 'Inactive';

    // Inquiry Status Constants
    public const INQUIRY_STATUS_OPEN = 'Open';
    public const INQUIRY_STATUS_CLOSED = 'Closed';
    public const INQUIRY_STATUS_PENDING = 'Pending for Payment';

    /**
     * Get the vehicle associated with the inquiry
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    /**
     * Get the user associated with the inquiry
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the customer associated with the inquiry
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Get the sales agent user associated with the inquiry
     */
    public function salesAgent()
    {
        return $this->belongsTo(User::class, 'sales_agent');
    }

    /**
     * Get the port/country associated with the inquiry
     */
    public function inquiryCountry()
    {
        return $this->belongsTo(Port::class, 'inqu_country', 'id');
    }

    /**
     * Get formatted reserved expiry date
     */
    public function getFormattedReservedExpiryDateAttribute()
    {
        return $this->reserved_expiry_date ? $this->reserved_expiry_date->format('M d, Y H:i') : null;
    }

    /**
     * Get the invoice associated with the inquiry
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        switch ($this->vehicle_status) {
            case self::STATUS_RESERVED:
                return 'bg-warning';
            case self::STATUS_READY_TO_SHIP:
                return 'bg-primary';
            case self::STATUS_OPEN:
                return 'bg-success';
            case self::STATUS_INACTIVE:
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    }

    /**
     * Get formatted total price (safe version without number_format)
     */
    public function getFormattedTotalPriceAttribute()
    {
        $price = $this->total_price ?? 0;
        // Handle any non-numeric values
        if (!is_numeric($price)) {
            $price = 0;
        }
        return '$' . $price;
    }

    /**
     * Get formatted FOB price (safe version without number_format)
     */
    public function getFormattedFobPriceAttribute()
    {
        $price = $this->fob_price ?? 0;
        // Handle any non-numeric values
        if (!is_numeric($price)) {
            $price = 0;
        }
        return '$' . $price;
    }

    /**
     * Safe numeric conversion for price fields
     */
    private function safeNumericValue($value)
    {
        if (is_null($value) || $value === '') {
            return 0;
        }

        // Remove any currency symbols and spaces
        $cleaned = preg_replace('/[^\d.-]/', '', $value);

        return is_numeric($cleaned) ? (float)$cleaned : 0;
    }

    /**
     * Get safe total price as float
     */
    public function getSafeTotalPriceAttribute()
    {
        return $this->safeNumericValue($this->total_price);
    }

    /**
     * Get safe FOB price as float
     */
    public function getSafeFobPriceAttribute()
    {
        return $this->safeNumericValue($this->fob_price);
    }

    /**
     * Get safe freight fee as float
     */
    public function getSafeFreightFeeAttribute()
    {
        return $this->safeNumericValue($this->freight_fee);
    }

    /**
     * Get safe insurance fee as float
     */
    public function getSafeInsuranceFeeAttribute()
    {
        return $this->safeNumericValue($this->insurance_fee);
    }

    /**
     * Get safe inspection fee as float
     */
    public function getSafeInspectionFeeAttribute()
    {
        return $this->safeNumericValue($this->inspection_fee);
    }

    /**
     * Get safe discount as float
     */
    public function getSafeDiscountAttribute()
    {
        return $this->safeNumericValue($this->discount);
    }
}



