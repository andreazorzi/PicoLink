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
        // Users
        Schema::create('users', function (Blueprint $table) {
            $table->string('username', 128);
            $table->string('email', 128);
            $table->string('name', 128);
            $table->text('password')->nullable();
            $table->text('groups');
            $table->text('type')->nullable();
            $table->text('avatar')->nullable();
            $table->boolean('enabled')->default(1);
            $table->rememberToken();

            $table->primary('username');
        });
        
        // Password resets
        Schema::create('password_resets', function (Blueprint $table) {
            $table->char('token', 64);
            $table->string('user', 128);
            $table->timestamp('expiration');
            $table->timestamp('created')->useCurrent();
            
            $table->primary('token');
            $table->foreign('user')->references('username')->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
