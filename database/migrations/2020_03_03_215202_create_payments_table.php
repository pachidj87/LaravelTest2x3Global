<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->date('payment_date')->nullable();
            $table->date('expires_at');
            $table->enum('status', ['pending', 'paid'])->default('pending');
            $table->bigInteger('user_id')->unsigned()->nullable(false);
            $table->float('clp_usd')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Adding relations
        Schema::table('payments', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payments');
    }
}
