<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Company;
use App\Models\StockCategory;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_subcategories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Company::class);
            $table->foreignIdFor(StockCategory::class);
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('active')->nullable();
            $table->text('Image')->nullable(); 

            $table->bigInteger('buying_price')->default(0)->nullable();
            $table->bigInteger('selling_price')->nullable()->default(0);
            
            $table->bigInteger('expected_profit')->nullable()->default(0);
            $table->bigInteger('earned_profit')->nullable()->default(0);
            $table->string('measuring_unit')->nullable()->default('pcs');
            $table->bigInteger('current_quantity')->nullable()->default(0);
            $table->bigInteger('reorder_level')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_subcategories');
    }
};
