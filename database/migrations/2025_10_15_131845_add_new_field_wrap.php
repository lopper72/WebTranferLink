<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('wraplinks', function (Blueprint $table) {
            $table->string('aff_link')->nullable(); // Thêm cột aff_link
        });
    }

    // ... existing code ...

    public function down()
    {
        Schema::table('wraplinks', function (Blueprint $table) {
            $table->dropColumn('aff_link'); // Xóa cột aff_link nếu cần
        });
    }
};