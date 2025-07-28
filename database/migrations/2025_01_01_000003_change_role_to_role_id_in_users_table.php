<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRoleToRoleIdInUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the old role column if it exists
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }

            // Add the new role_id column as foreign key if it doesn't exist
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->unsignedBigInteger('role_id')->after('status');
                $table->foreign('role_id')->references('id')->on('roles');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key and role_id column
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            
            // Add back the old role column
            $table->integer('role')->after('status');
        });
    }
}
