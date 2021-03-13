<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletLockHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_lock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id');
            $table->foreignId('user_id');
            $table->foreignId('order_id');
            $table->enum('payment_status', ['successful', 'failed']);
            $table->softDeletes();
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
        Schema::dropIfExists('wallet_lock_histories');
    }
}
