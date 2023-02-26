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
        Schema::create('aaa_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable();
        });
    }

    public function down(): void
    {
        if (config("aaa.upstream") !== null) {
            return;
        }
        Schema::dropIfExists('aaa_types');
    }
};
