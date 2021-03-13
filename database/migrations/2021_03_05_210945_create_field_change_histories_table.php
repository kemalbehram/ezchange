<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldChangeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_change_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');       /* which user's info was changed */
            $table->foreignId('changed_by');    /* who changed the info  */
            $table->string('field_name');
            $table->string('changed_from');
            $table->string('changed_to');
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
        Schema::dropIfExists('field_change_histories');
    }
}
