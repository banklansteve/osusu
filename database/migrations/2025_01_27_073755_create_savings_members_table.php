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
        Schema::create('savings_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('savings_id')->constrained('savings')->onDelete('cascade'); // Savings group
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Member
            $table->integer('payout_turn')->nullable();
            $table->decimal('payout_amount', 7, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings_members');
    }
};
