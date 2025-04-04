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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('github_id')->unique('github_id');
            $table->text('url')->nullable();
            $table->text('html_url')->nullable();
            $table->text('location')->nullable();
            $table->text('hireable')->nullable();
            $table->text('name')->nullable();
            $table->text('email')->nullable();
            $table->text('twitter_username')->nullable();
            $table->text('blog')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
