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
        Schema::table('stock_records', function (Blueprint $table) {
            $table->unsignedBigInteger('stock_category_id')->nullable()->after('qty');
            $table->unsignedBigInteger('stock_subcategory_id')->nullable()->after('stock_category_id');
            
            // If you want foreign keys (optional):
            // $table->foreign('stock_category_id')->references('id')->on('stock_categories');
            // $table->foreign('stock_subcategory_id')->references('id')->on('stock_subcategories');
        });
    }

    public function down()
    {
        Schema::table('stock_records', function (Blueprint $table) {
            $table->dropColumn(['stock_category_id', 'stock_subcategory_id']);
        });
    }

};
