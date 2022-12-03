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
        Schema::create('purchase_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("customer_id");
            $table->foreign("customer_id")->references("id")->on("customers");
            $table->decimal("total_spent", 10, 2);
            $table->decimal("total_saving", 10, 2);
            $table->timestamp("transaction_at");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("purchase_transactions", function (Blueprint $table) {
            $table->dropForeign("purchase_transactions_customer_id_foreign");
        });
        Schema::dropIfExists('purchase_transactions');
    }
};
