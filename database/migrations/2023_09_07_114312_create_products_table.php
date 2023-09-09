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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->uuid('category_uuid');
            $table->string('title');
            $table->float('price');
            $table->text('description');
            $table->json('metadata');
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key Constraint
            $table->foreign('category_uuid')
                ->references('uuid')
                ->on('categories'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }

    
};
