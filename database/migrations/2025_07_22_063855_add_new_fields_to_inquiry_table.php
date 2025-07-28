<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToInquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inquiry', function (Blueprint $table) {
            // Add new fields for enhanced inquiry system (only if they don't exist)
            if (!Schema::hasColumn('inquiry', 'freight_fee')) {
                $table->string('freight_fee')->nullable()->after('fob_price');
            }
            if (!Schema::hasColumn('inquiry', 'insurance_fee')) {
                $table->string('insurance_fee')->nullable()->after('freight_fee');
            }
            if (!Schema::hasColumn('inquiry', 'inspection_fee')) {
                $table->string('inspection_fee')->nullable()->after('insurance_fee');
            }
            if (!Schema::hasColumn('inquiry', 'discount')) {
                $table->string('discount')->nullable()->after('inspection_fee');
            }
            if (!Schema::hasColumn('inquiry', 'sales_agent')) {
                $table->string('sales_agent')->nullable()->after('inqu_comment');
            }
            if (!Schema::hasColumn('inquiry', 'customer_id')) {
                $table->string('customer_id')->nullable()->after('sales_agent');
            }
            if (!Schema::hasColumn('inquiry', 'vehicle_status')) {
                $table->string('vehicle_status')->default('Reserved')->after('customer_id');
            }
            if (!Schema::hasColumn('inquiry', 'reserved_expiry_date')) {
                $table->datetime('reserved_expiry_date')->nullable()->after('vehicle_status');
            }
            if (!Schema::hasColumn('inquiry', 'final_country')) {
                $table->string('final_country')->nullable()->after('reserved_expiry_date');
            }
            if (!Schema::hasColumn('inquiry', 'port_name')) {
                $table->string('port_name')->nullable()->after('final_country');
            }
            if (!Schema::hasColumn('inquiry', 'type_of_purchase')) {
                $table->string('type_of_purchase')->nullable()->after('port_name');
            }
            if (!Schema::hasColumn('inquiry', 'status')) {
                $table->string('status')->default('Open')->after('inqu_comment');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inquiry', function (Blueprint $table) {
            // Drop the new fields
            $table->dropColumn([
                'freight_fee',
                'insurance_fee',
                'inspection_fee',
                'discount',
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
            ]);
        });
    }
}
