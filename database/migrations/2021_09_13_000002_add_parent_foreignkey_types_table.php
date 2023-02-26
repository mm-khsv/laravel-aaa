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
        Schema::table('aaa_types', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('aaa_types');
        });
    }

    public function down(): void
    {
        if (config("aaa.upstream") !== null) {
            return;
        }
        Schema::table('aaa_types', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
        });
    }
};
