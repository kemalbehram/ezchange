<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnTypesInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'amount_in_rials')) {
                    $table->unsignedDecimal('amount_in_rials')->nullable()->change();
                }
                if (Schema::hasColumn('orders', 'amount_in_tethers')) {
                    $table->unsignedDecimal('amount_in_tethers')->nullable()->change();
                }
                if (Schema::hasColumn('orders', 'price_in_rials')) {
                    $table->unsignedDecimal('price_in_rials')->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
