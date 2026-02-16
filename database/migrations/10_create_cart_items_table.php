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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('cart_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assembly_id')->nullable()->constrained('assemblies')->onDelete('cascade');
            $table->foreignId('component_id')->nullable()->constrained('components')->onDelete('cascade');
            $table->foreignId('custom_assembly_id')->nullable()->constrained('custom_assemblies');
            $table->integer('quantity')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
