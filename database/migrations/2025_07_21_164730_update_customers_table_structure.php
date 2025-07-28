<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCustomersTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            // Drop columns that don't match the new structure (only if they exist)
            $columnsToCheck = ['country', 'city', 'address', 'phone', 'email_verified_at', 'avatar', 'comment_status'];
            $columnsToDrop = [];

            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('customers', $column)) {
                    $columnsToDrop[] = $column;
                }
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }

            // Add country_id column if it doesn't exist
            if (!Schema::hasColumn('customers', 'country_id')) {
                $table->unsignedBigInteger('country_id')->nullable()->after('email');
            }

            // Add soft deletes if not exists
            if (!Schema::hasColumn('customers', 'deleted_at')) {
                $table->softDeletes();
            }

            // Note: Column modifications using change() are commented out due to doctrine/dbal compatibility issues
            // The table structure should already be correct from the initial migration
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            // Add back the dropped columns
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('avatar')->nullable();
            $table->boolean('comment_status')->default(0);

            // Drop country_id
            $table->dropColumn('country_id');

            // Revert name column to not nullable
            $table->string('name')->nullable(false)->change();

            // Revert status column to boolean
            $table->boolean('status')->default(1)->change();

            // Drop soft deletes
            $table->dropSoftDeletes();
        });
    }
}
