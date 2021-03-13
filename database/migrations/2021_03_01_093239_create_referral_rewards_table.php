<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('referral_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referent_id');
            $table->foreignId('referrer_id');
            $table->timestamps();



//            $table->foreign('referent_id')
//                ->references('id')
//                ->on('users')
//                ->cascadeOnDelete()
//                ->cascadeOnUpdate();
//            $table->foreign('referrer_id')
//                ->references('id')
//                ->on('users')
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
        Schema::dropIfExists('referral_rewards');
    }
}
