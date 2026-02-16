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
        Schema::create('custom_assembly_components', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('custom_assembly_id')->constrained('custom_assemblies')->onDelete('cascade');
            $table->foreignId('component_id')->constrained('components')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_assembly_components');
    }
};
