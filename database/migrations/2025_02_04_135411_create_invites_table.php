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
        Schema::create('invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('saving_id')->constrained()->onDelete('cascade');
            $table->string('invite_token')->unique(); // Unique token for the invite
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending'); // Invite status
            $table->timestamp('sent_at')->nullable(); // When the invite was sent
            $table->timestamp('accepted_at')->nullable(); // When the invite was accepted
            $table->enum('invite_method', ['email', 'sms', 'whatsapp', 'in-app'])->default('email'); // Invite method
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invites');
    }
};
