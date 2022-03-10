<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SvSlBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sv_sl_booking', function (Blueprint $table) {
            $table->id();
            $table->integer('sale_or_id');
            $table->integer('mm_sl_acc_id');
            $table->integer('mm_ms_acc_id');
            $table->string('sl_qr_no');
            $table->string('sl_qr_exp');
            $table->integer('ref_qr_no')->nullable();
            $table->double('amt_deducted');
            $table->double('balance_amt');
            $table->string('qr_status');
            $table->text('qr_data');
            $table->dateTime('txn_date');
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
