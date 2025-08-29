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
        Schema::table('admin_users', function (Blueprint $table) {
            $table->integer('company_id')->nullable();
            $table->text('firstname')->nullable()->after('company_id');
            $table->text('lastname')->nullable()->after('firstname');
            $table->text('phone1')->nullable()->after('lastname');
            $table->text('phone2')->nullable()->after('phone1');
            $table->text('sex')->nullable()->after('phone2');
            $table->date('dob')->nullable()->after('sex');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_users', function (Blueprint $table) {
            //
        });
    }
};
