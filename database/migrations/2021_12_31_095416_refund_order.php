<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RefundOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_order', function (Blueprint $table) {
            $table->id('rf_id');
            $table->string('ref_or_no');
            $table->integer('sale_or_id');;
            $table->integer('pax_id');
            $table->integer('unit');
            $table->double('ref_amt');
            $table->double('ref_chr');
            $table->string('app_or_no')->nullable();
            $table->string('app_txn_no')->nullable();
            $table->integer('ref_or_status')->default(1);
            $table->dateTime('txn_date'); // travel date
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
