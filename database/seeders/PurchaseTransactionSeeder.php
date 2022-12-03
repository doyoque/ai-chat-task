<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PurchaseTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 0; $i < 30; $i++) {
            DB::table("purchase_transactions")->insert([
                "customer_id" => $faker->numberBetween(1, 10),
                "total_spent" => $faker->numberBetween(1, 100),
                "total_saving" => $faker->numberBetween(1, 100),
                "transaction_at" => $faker->dateTimeBetween('-30 days'),
            ]);
        }
    }
}
