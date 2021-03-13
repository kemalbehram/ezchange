<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMobileIsVrifiedColumnToUsersTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
               if (!Schema::hasColumn('users', 'mobile_is_verified')) {
                   $table->enum('mobile_is_verified', [0, 1])->default(0)->after('email_verified_at');
               }
            });
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mobile_is_verified');
        });
    }
}
