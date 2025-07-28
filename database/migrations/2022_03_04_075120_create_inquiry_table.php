<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('vehicle_name');
            $table->string('fob_price')->nullable();
            $table->string('freight_fee')->nullable();
            $table->string('insurance_fee')->nullable();
            $table->string('inspection_fee')->nullable();
            $table->string('discount')->nullable();
            $table->string('inqu_port')->nullable();
            $table->string('total_price')->nullable();
            $table->string('site_url');
            $table->string('inqu_name');
            $table->string('inqu_email');
            $table->string('inqu_mobile');
            $table->string('inqu_country');
            $table->string('inqu_address')->nullable();
            $table->string('inqu_city')->nullable();
            $table->string('stock_no')->nullable();
            $table->integer('vehicle_id')->nullable();
            $table->longText('inqu_comment')->nullable();

            // New fields for enhanced inquiry system
            $table->string('sales_agent')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('vehicle_status')->default('Reserved');
            $table->datetime('reserved_expiry_date')->nullable();
            $table->string('final_country')->nullable();
            $table->string('port_name')->nullable();
            $table->string('type_of_purchase')->nullable();
            $table->string('insurance')->nullable();
            $table->string('inspection')->nullable();
            $table->string('status')->default('Open');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inquiry');
    }
}
