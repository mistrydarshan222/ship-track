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
        Schema::create('change_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Admin who made the change
            $table->string('model'); // Model name (e.g., Product, Shipping, Purchase)
            $table->unsignedBigInteger('model_id'); // ID of the model that was changed
            $table->string('action'); // Action type (e.g., created, updated, deleted)
            $table->text('changes')->nullable(); // Details of the changes made
            $table->timestamps();
        
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('change_logs');
    }
};
