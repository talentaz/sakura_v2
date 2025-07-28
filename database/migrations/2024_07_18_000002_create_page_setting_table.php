<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_setting', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('keywords')->nullable();
            $table->string('page_type')->default('inner_page');
            $table->string('slug')->unique();
            $table->string('url', 500)->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->boolean('on_menu')->default(true);
            $table->integer('on_menu_order')->default(0);
            $table->boolean('on_footer')->default(false);
            $table->string('footer_section')->nullable();
            $table->longText('editor_content');
            $table->longText('plain_content');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['is_active', 'on_menu']);
            $table->index(['is_active', 'on_footer']);
            $table->index(['page_type', 'is_active']);
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_setting');
    }
}
