<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->integer('stn_id')->unique();
            $table->string('stn_name')->unique();
            $table->string('stn_code')->unique();
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
        Schema::dropIfExists('stations');
    }
}

/*INSERT INTO `stations` (`stn_id`, `stn_name`, `stn_code`) VALUES
(1, 'Versova', 'VER'),
(2, 'DN Nagar', 'DNG'),
(3, 'Azad Nagar', 'AZN'),
(4, 'Andheri', 'AND'),
(5, 'Western Express Highway', 'WEH'),
(6, 'Chakala JB Nagar', 'CHK'),
(7, 'Airport Road', 'APR'),
(8, 'Marol Naka', 'MAN'),
(9, 'Saki Naka', 'SAN'),
(10, 'Asalpha', 'ASA'),
(11, 'Jagruti Nagar', 'JNG'),
(12, 'Ghatkopar', 'GHA');*/
