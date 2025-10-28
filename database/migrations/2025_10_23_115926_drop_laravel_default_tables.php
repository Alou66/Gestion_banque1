<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Supprimer les tables Laravel par défaut si elles existent
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('personal_access_tokens');
        Schema::dropIfExists('failed_jobs');
    }

    public function down(): void
    {
        // Si tu veux, tu peux recréer les tables en cas de rollback
    }
};
