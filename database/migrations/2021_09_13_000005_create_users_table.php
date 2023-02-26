<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('aaa_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('type_id');
            $table->tinyInteger('status');

            $table->index(['status']);
            if (config("aaa.upstream") === null) {
                $table->foreign('type_id')
                    ->references('id')
                    ->on('aaa_types');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aaa_users');
    }
};
