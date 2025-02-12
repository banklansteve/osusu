<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('uuid')->after('name')->nullable();
            $table->string('first_name')->after('uuid')->nullable();
            $table->string('last_name')->after('first_name')->nullable();
            $table->string('phone')->after('last_name')->nullable();
            $table->string('profile_pic')->after('phone')->nullable();
            $table->string('post_code')->after('profile_pic')->nullable();
            $table->string('first_line_address')->after('post_code')->nullable();
            $table->string('acct_no')->after('first_line_address')->nullable();
            $table->string('sort_code')->after('acct_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
