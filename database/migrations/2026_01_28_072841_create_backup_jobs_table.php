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
        Schema::create('backup_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backup_server_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['files', 'db']);
            $table->string('cron');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['backup_server_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_jobs');
    }
};
