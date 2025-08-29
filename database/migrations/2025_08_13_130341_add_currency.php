<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            // Add a new column for currency
            $table->string('currency')->default('USD')->nullable();
            $table->string('setting_worker_can_create_stock_item')->default('1')->nullable();
            $table->string('setting_worker_can_create_stock_record')->default('1')->nullable();
            $table->string('setting_worker_can_create_stock_category')->default('1')->nullable();
            $table->string('setting_worker_can_view_stock_item')->default('1')->nullable();
            $table->string('setting_worker_can_view_stock_record')->default('1')->nullable();
            $table->string('setting_worker_can_view_stock_category')->default('1')->nullable();
            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            //
        });
    }
};
