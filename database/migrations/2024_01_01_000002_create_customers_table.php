<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // bigint(20) unsigned auto_increment primary key
            $table->string('name')->nullable(); // varchar(255) nullable
            $table->string('email')->unique(); // varchar(255) unique not null
            $table->unsignedBigInteger('country_id')->nullable(); // bigint(20) unsigned nullable with foreign key
            $table->string('password'); // varchar(255) not null
            $table->rememberToken(); // varchar(100) nullable
            $table->string('status')->default('Active'); // varchar(255) not null default 'Active'
            $table->timestamps(); // created_at and updated_at
            $table->softDeletes(); // deleted_at timestamp nullable

            // Add foreign key constraint for country_id if countries table exists
            // $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}