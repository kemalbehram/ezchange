<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->unsignedDecimal('amount_in_tethers', 50, 10);
            $table->unsignedDecimal('amount_in_rials', 50, 10);
            $table->unsignedDecimal('price_in_rials', 50, 10);
            $table->enum('type', ['buy', 'sell'])->default('buy');
            $table->foreignId('from_wallet_id')->nullable();
            $table->foreignId('to_wallet_id')->nullable();
            $table->string('from_wallet')->nullable();
            $table->string('to_wallet')->nullable();
            $table->enum('status', ['banned', 'process', 'complete', 'unconfirmed'])->default('process');
            $table->foreignId('tx_id')->nullable();
            $table->enum('payment_status', [0, 1])->default(0);
            $table->timestamp('pay_time')->nullable();
            $table->string('bin_id')->nullable();
            $table->string('bin_status')->nullable();
            $table->string('bin_tx_id')->nullable();
            $table->foreignId('bank_card_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

//            $table->foreign('user_id')
//                ->references('id')
//                ->on('users')
//                ->cascadeOnDelete()
//                ->cascadeOnUpdate();
//            $table->foreign('tx_id')
//                ->references('id')
//                ->on('transactions')
//                ->cascadeOnDelete()
//                ->cascadeOnUpdate();
//            $table->foreign('bank_card_id')
//                ->references('id')
//                ->on('bank_cards')
//                ->cascadeOnDelete()
//                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
