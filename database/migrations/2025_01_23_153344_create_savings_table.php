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
        Schema::create('savings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->decimal('saving_amount, 5, 2');
            $table->integer('total_members');
            $table->integer('savings_duration');
            $table->foreignId('payout_turn_method_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->defaultValue('pending');
            $table->enum('payment_frequency', ['weekly', 'monthly'])->defaultValue('monthly');
            $table->decimal('penalty_rate', 5, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings');
    }
};
