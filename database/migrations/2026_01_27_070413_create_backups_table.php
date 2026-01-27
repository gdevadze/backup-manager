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
        Schema::create('backups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('backup_server_id')->constrained()->cascadeOnDelete();

            $table->date('backup_date');
            $table->string('path'); // /backups/web1/2026-01-25
            $table->unsignedBigInteger('size_bytes')->nullable();

            $table->enum('status', ['running','success','failed'])->default('running');
            $table->longText('log')->nullable();
            $table->string('error_code')->nullable();

            $table->timestamps();

            $table->unique(['backup_server_id', 'backup_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};
