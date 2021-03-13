<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_numbers', function (Blueprint $table) {
            $table->id();
            /* what is the structure 09147408668 or 9147408668 or 00989147408668 */
            $table->string('mobile_number');
            $table->foreignId('user_id');
            $table->enum('is_verified', [0, 1, 2])->default(0);
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
        Schema::dropIfExists('mobile_numbers');
    }
}
