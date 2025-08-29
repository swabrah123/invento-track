<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Company;
use App\Models\FinancialPeriod;
use App\Models\StockCategory;
use App\Models\StockSubcategory;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Company::class);
            $table->foreignIdFor(User::class, 'created_by');
            $table->foreignIdFor(StockCategory::class);
            $table->foreignIdFor(StockSubcategory::class);
            $table->foreignIdFor(FinancialPeriod::class);
            $table->text('name');
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->text('barcode')->nullable();
            $table->text('sku')->nullable();
            $table->text('genarate_sku')->nullable();
            $table->string('gallery')->nullable();

            $table->bigInteger('buying_price')->default(0);
            $table->bigInteger('selling_price')->default(0);
            $table->bigInteger('original_qty')->default(0);
            $table->bigInteger('current_qty')->default(0);




            





           



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_items');
    }
};
