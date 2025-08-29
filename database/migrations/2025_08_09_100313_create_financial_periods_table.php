<?php

use App\Models\Company;
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
        Schema::create('financial_periods', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor(Company::class);
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status')->default('active')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('total_Investiment')->default(0)->nullable();
            $table->bigInteger('total_sales')->default(0)->nullable();
            $table->bigInteger('Total_profit')->default(0)->nullable();
            $table->bigInteger('total_expenses')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('financial_periods');
    }
};
