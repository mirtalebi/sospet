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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->enum('type', ['lost', 'found', 'adoption']); 
            $table->string('title'); 
            $table->string('pet_type'); 
            $table->string('breed')->nullable(); 
            $table->string('gender')->nullable(); 
            $table->string('age')->nullable(); 
            
            $table->string('city'); 
            $table->string('area')->nullable(); 
            
            // فیلدهای موقعیت جغرافیایی روی نقشه
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            $table->string('phone'); 
            $table->text('description')->nullable();
            $table->boolean('is_resolved')->default(false); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
