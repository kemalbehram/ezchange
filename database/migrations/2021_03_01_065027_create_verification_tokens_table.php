<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerificationTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verification_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignId('user_id');
            $table->enum('is_used', [0, 1])->default(0); /* 0 => is not used, 1 => is used */
            $table->timestamps();

//            $table->foreign('user_id')
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
        Schema::dropIfExists('verification_tokens');
    }
}
