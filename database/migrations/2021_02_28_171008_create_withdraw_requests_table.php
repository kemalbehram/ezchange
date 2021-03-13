<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('tx_id');
            $table->string('wallet_id');
            $table->foreignId('bank_card_id')->nullable();
            $table->string('card_number')->nullable();
            $table->unsignedDecimal('amount', 50, 3);
            $table->enum('status', ['banned', 'process', 'complete']);
            $table->string('description')->nullable();
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
//            $table->foreign('waleet_id')
//                ->references('id')
//                ->on('wallets')
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
        Schema::dropIfExists('withdraw_requests');
    }
}
