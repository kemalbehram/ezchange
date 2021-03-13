<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposit_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('tx_id');
            /* if the entered card exists in the db then take the request to process else ban
             it until the card is registered and verified */
            $table->foreignId('bank_card_id')->nullable();
            $table->string('card_number')->nullable();
            $table->unsignedDecimal('amount', 50, 3);
            $table->enum('status', [0, 1, 2]);
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
        Schema::dropIfExists('deposit_requests');
    }
}
