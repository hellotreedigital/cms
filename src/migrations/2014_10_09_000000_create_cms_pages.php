<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('icon')->nullable();
            $table->string('display_name');
            $table->string('display_name_plural');
            $table->string('database_table')->unique();
            $table->string('route')->unique();
            $table->string('model_name')->unique();
            $table->string('controller_name')->unique();
            $table->string('migration_name')->unique();
            $table->string('order_display')->nullable();
            $table->longtext('fields');
            $table->string('page_type');
            $table->string('parent_title')->nullable();
            $table->string('parent_icon')->nullable();
            $table->longtext('notes')->nullable();;
            $table->tinyInteger('deletable')->default(1);
            $table->tinyInteger('ht_pos')->nullable();
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
        Schema::dropIfExists('cms_pages');
    }
}
