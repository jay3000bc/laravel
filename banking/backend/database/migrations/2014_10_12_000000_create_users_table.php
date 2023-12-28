<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->string('otp')->nullable();
            $table->double('balance', 8, 2)->default(0);
            $table->string('otp_expires_at')->nullable();
            $table->timestamp('otp_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('transaction_otp')->nullable();
            $table->string('transaction_otp_expires_at')->nullable();
            $table->timestamp('transaction_otp_verified_at')->nullable();
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
        Schema::dropIfExists('users');
    }
};
