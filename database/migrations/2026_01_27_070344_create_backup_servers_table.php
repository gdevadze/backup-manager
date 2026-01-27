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
        Schema::create('backup_servers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g. web1
            $table->string('host');           // IP/Domain
            $table->string('ssh_user');
            $table->unsignedInteger('ssh_port')->default(22);

            $table->string('remote_path'); // e.g. /var/www/project
            $table->text('exclude_args')->nullable(); // tar exclude args

            // optional DB
            $table->string('db_name')->nullable();
            $table->string('db_user')->nullable();
            $table->text('db_password')->nullable(); // encrypted

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_servers');
    }
};
