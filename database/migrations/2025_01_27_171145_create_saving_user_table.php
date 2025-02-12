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
        Schema::create('saving_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saving_id')->constrained()->onDelete('cascade'); // Foreign key to savings table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');   // Foreign key to users table
            $table->integer('payout_turn')->nullable();
            $table->decimal('payout_amount', 7, 2)->nullable();
            $table->unique(['user_id', 'saving_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saving_user');
    }
};
