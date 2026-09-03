<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('knowledge_articles')) {
            Schema::create('knowledge_articles', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->string('category')->default('General Guides');
                $table->string('icon')->default('fa-book');
                $table->text('summary')->nullable();
                $table->longText('content');
                $table->integer('views_count')->default(0);
                $table->boolean('is_published')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_articles');
    }
};
