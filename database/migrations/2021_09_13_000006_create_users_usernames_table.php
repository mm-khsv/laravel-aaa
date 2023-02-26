<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        if (config("aaa.upstream") !== null) {
            return;
        }
        Schema::create('aaa_users_usernames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('username')->unique();
            $table->string('password', 255)->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('aaa_users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        if (config("aaa.upstream") !== null) {
            return;
        }
        Schema::dropIfExists('aaa_users_usernames');
    }
};
