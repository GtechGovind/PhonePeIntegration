<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SaleOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_order', function (Blueprint $table) {

            $table->id('sale_or_id');
            $table->string('sale_or_no');
            $table->dateTime('txn_date'); // record date
            $table->integer('pax_id');
            $table->integer('src_stn_id')->nullable();
            $table->integer('des_stn_id')->nullable();

            $table->integer('mm_ms_acc_id')->nullable();
            $table->string('ms_qr_no')->nullable();
            $table->dateTime('ms_qr_exp')->nullable();

            $table->integer('unit');
            $table->double('unit_price');
            $table->double('total_price');

            $table->integer('op_type_id');
            $table->integer('media_type_id');
            $table->integer('product_id');
            $table->integer('pass_id');

            $table->integer('pg_id')->nullable();

            $table->string('pg_txn_no')->nullable();

            $table->string('ref_sl_qr')->nullable();

            $table->integer('sale_or_status')->default(1);
            $table->timestamp('insert_date')->default(DB::raw('CURRENT_TIMESTAMP'));

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
