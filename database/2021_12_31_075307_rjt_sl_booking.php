<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RjtSlBooking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rjt_sl_booking', function (Blueprint $table) {
            $table->id();
            $table->integer('sale_or_id');
            $table->integer('mm_ms_acc_id');
            $table->integer('mm_sl_acc_id');
            $table->string('sl_qr_no');
            $table->dateTime('sl_qr_exp');
            $table->string('ref_qr_no')->nullable();
            $table->integer('qr_dir');
            $table->integer('qr_status')->default(1);
            $table->text('qr_data');
            $table->dateTime('txn_date'); // record date
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
