<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInvoiceTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Remove paid_amount and description columns
            $table->dropColumn(['paid_amount', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Add back the columns if needed to rollback
            $table->decimal('paid_amount', 10, 2)->after('inquiry_id');
            $table->text('description')->nullable()->after('paid_amount');
        });
    }
}
