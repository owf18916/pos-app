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
        Schema::create('cash_flows', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['in', 'out']);
            $table->enum('source', ['cash', 'bank']);
            $table->string('source_type')->default('manual');
            $table->string('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->timestamp('date');
            $table->foreignId('sale_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_flows');
    }
};
