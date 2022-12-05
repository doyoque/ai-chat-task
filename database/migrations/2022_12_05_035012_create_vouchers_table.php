<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string("code", 255);
            $table->unsignedBigInteger("customer_id")->nullable();
            $table->foreign("customer_id")->references("id")->on("customers");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("vouchers", function (Blueprint $table) {
            $table->dropForeign("vouchers_customer_id_foreign");
        });
        Schema::dropIfExists('vouchers');
    }
};
