<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 0; $i < 1000; $i++) {
            DB::table("vouchers")->insert([
                "code" => $faker->regexify("[A-Z]{5}[0-9]{3}"),
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        }
    }
}
