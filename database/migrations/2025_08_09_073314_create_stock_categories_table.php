<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Company;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('stock_categories', function (Blueprint $table) {
    $table->id();
    $table->foreignIdFor(Company::class);
    $table->string('category_name');
    $table->text('description')->nullable();
    $table->string('status')->default('active')->nullable();
    $table->text('Image')->nullable(); // storing image filename or path
    $table->bigInteger('buying_price')->default(0);
    $table->bigInteger('selling_price');
    $table->bigInteger('expected_profit');
    $table->bigInteger('earned_profit');
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
        Schema::dropIfExists('stock_categories');
    }
};
