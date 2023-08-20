<?php

namespace Database\Seeders;

use App\Jobs\CreateUserJob;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    const chunkSize = 200;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tắt ghi log truy vấn cơ sở dữ liệu
        DB::disableQueryLog();

        $totalUsers = 100000;
        $numChunks = ceil($totalUsers / UserSeeder::chunkSize);

        // Bắt đầu giao dịch cơ sở dữ liệu
//        DB::beginTransaction();

        for ($i = 0; $i < $numChunks; $i++) {
            dispatch(new CreateUserJob());
        }

        // Kết thúc giao dịch cơ sở dữ liệu
//        DB::commit();
    }
}

