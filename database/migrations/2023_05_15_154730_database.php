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
        
        // Shorts
        Schema::create('shorts', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('code', 50)->unique();
            $table->string('description');
            $table->dateTime('created_at')->useCurrent();
        });
        
        // Urls
        Schema::create('urls', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('short_id');
            $table->string('url');
            $table->char('language', 2)->nullable();
            
            $table->foreign('short_id')->references('id')->on('shorts')->onUpdate('cascade')->onDelete('cascade');
        });
        
        // Visits
        Schema::create('visits', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('short_id')->nullable();
            $table->integer('url_id')->nullable();
            $table->string('language');
            $table->string('device', 50);
            $table->string('country', 2)->nullable();
            $table->string('referer');
            $table->dateTime('created_at')->useCurrent();
            
            $table->foreign('short_id')->references('id')->on('shorts')->onUpdate('cascade')->nullOnDelete();
            $table->foreign('url_id')->references('id')->on('urls')->onUpdate('cascade')->nullOnDelete();
        });
        
        // Tag Categories
        Schema::create('tag_categories', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('name');
        });
        
        // Tags
        Schema::create('tags', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('tag_category_id');
            $table->string('name');
            
            $table->foreign('tag_category_id')->references('id')->on('tag_categories')->onUpdate('cascade')->onDelete('cascade');
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
