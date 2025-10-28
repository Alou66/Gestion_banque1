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
        Schema::create('comptes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('numero')->unique();
            $table->enum('type', ['epargne', 'cheque']);
            $table->decimal('solde', 15, 2)->default(0);
            $table->enum('statut', ['actif', 'bloque', 'ferme'])->default('actif');
            $table->foreignUuid('client_id')->constrained('clients')->onDelete('cascade');
            $table->text('motif_blocage')->nullable();
            $table->boolean('supprime')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comptes');
    }
};
