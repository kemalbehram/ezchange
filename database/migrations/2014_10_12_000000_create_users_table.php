
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status', [0, 1])->default(1);
            $table->enum('is_verified', ['banned', 'process', 'verified'])->default('process');
            $table->string('parent_name')->nullable();
            $table->string('national_code')->unique()->nullable();
            $table->date('birthdate')->nullable();
            $table->string('mobile_number')->unique();
            $table->string('phone_number')->nullable();
            $table->string('referral_code')->unique()->nullable();
            $table->string('docs_path')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->timestamp('last_login_date')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->string('api_token')->nullable();
            $table->string('field_verification')->nullable();
            $table->string('reset_pass_hash')->nullable();
            $table->string('mobile_verification_hash')->nullable();
            $table->string('phone_verification_hash')->nullable();
            $table->rememberToken();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
