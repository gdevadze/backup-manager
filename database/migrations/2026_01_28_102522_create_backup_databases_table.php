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
        Schema::create('backup_databases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backup_server_id')->constrained()->cascadeOnDelete();

            $table->string('name'); // label: main_db, billing, wordpress
            $table->string('db_host')->default('localhost');
            $table->unsignedInteger('db_port')->default(3306);
            $table->string('db_name');
            $table->string('db_user');
            $table->text('db_password'); // encrypted
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_databases');
    }
};
