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
    Schema::create('stock_records', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('company_id');
        $table->unsignedBigInteger('created_by');
        $table->unsignedBigInteger('stock_item_id');  // link to stock_items table
        $table->string('type');  // sale, damage, etc.
        $table->text('description')->nullable();
        $table->decimal('qty', 10, 2);
        $table->timestamps();

        // Foreign keys (optional but recommended)
        $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
$table->foreign('created_by')->references('id')->on('admin_users')->onDelete('cascade');
        $table->foreign('stock_item_id')->references('id')->on('stock_items')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_records');
    }
};
