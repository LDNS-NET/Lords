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
        Schema::create('renters', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('id_number')->unique();
            $table->foreignId('apartment_id')->constrained()->onDelete('cascade');
            $table->string('house_number');
            $table->date('move_in_date');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Link to user account if created
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renters');
    }
};
