<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_cards', function (Blueprint $table) {
            $table->id();
            $table->enum('status', [0, 1])->default(1);
            $table->enum('is_verified', [0, 1, 2])->default(1); /* 0 => banned, 1 => in process, 2 => verified */
            $table->foreignId('user_id');
            $table->string('owner_first_name');
            $table->string('owner_last_name');
            $table->string('bank');
            $table->string('account_number');
            $table->string('card_number', 16);
            $table->string('iban');
            $table->tinyInteger('deposit'); /* 0 => can't be used for deposit, 1 => can be used for deposit */
            $table->tinyInteger('withdraw'); /* 0 => can't be used for withdraw, 1 => can be used for withdraw */
            $table->softDeletes();
            $table->timestamps();

//            $table->foreign('user_id')
//                ->references('id')
//                ->on('users')
//                ->cascadeOnDelete()
//                ->cascadeOnUpdate();
//            $table->foreign('bank_id')
//                ->references('id')
//                ->on('banks')
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
        Schema::dropIfExists('bank_cards');
    }
}
