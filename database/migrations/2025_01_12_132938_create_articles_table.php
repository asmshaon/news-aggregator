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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('source_id')->constrained('sources');
            $table->foreignId('author_id')->constrained('authors');
            $table->foreignId('category_id')->constrained('categories');
            $table->string('url', 1000)->nullable();
            $table->string('image', 500)->nullable();
            $table->timestamps();

            // Indexes for fast search
            $table->index('title');
            // Full-text index for better search on description
            $table->fullText('description', 'articles_description_fulltext');
            $table->index('source_id');
            $table->index('author_id');
            $table->index('category_id');
            $table->index(['source_id', 'author_id', 'category_id']); // Composite index for filtering by source, author, and category
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
