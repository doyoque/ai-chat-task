<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 0; $i < 10; $i++) {
            DB::table("customers")->insert([
                "first_name" => $faker->firstName(),
                "last_name" => $faker->lastName(),
                "gender" => $faker->randomElement(["male", "female"]),
                "date_of_birth" => now(),
                "contact_number" => "+6287723472321",
                "email" => $faker->email(),
                "created_at" => now(),
                "updated_at" => now(),
            ]);
        }
    }
}
