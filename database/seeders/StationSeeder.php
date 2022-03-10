<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StationSeeder extends Seeder
{
    public function run()
    {
        DB::statement("INSERT INTO `stations` (`id`, `stn_id`, `stn_name`, `stn_code`, `insert_date`) VALUES
            (1, 1, 'Versova', 'VER', '2022-02-08 08:14:46'),
            (2, 2, 'DN Nagar', 'DNG', '2022-02-08 08:14:46'),
            (3, 3, 'Azad Nagar', 'AZN', '2022-02-08 08:14:46'),
            (4, 4, 'Andheri', 'AND', '2022-02-08 08:14:46'),
            (5, 5, 'Western Express', 'WEH', '2022-02-08 08:14:46'),
            (6, 6, 'Chakala JB Nagar', 'CHK', '2022-02-08 08:14:46'),
            (7, 7, 'Airport Road', 'APR', '2022-02-08 08:14:46'),
            (8, 8, 'Marol Naka', 'MAN', '2022-02-08 08:14:46'),
            (9, 9, 'Saki Naka', 'SAN', '2022-02-08 08:14:46'),
            (10, 10, 'Asalpha', 'ASA', '2022-02-08 08:14:46'),
            (11, 11, 'Jagruti Nagar', 'JNG', '2022-02-08 08:14:46'),
            (12, 12, 'Ghatkopar', 'GHA', '2022-02-08 08:14:46');"
        );
    }
}
