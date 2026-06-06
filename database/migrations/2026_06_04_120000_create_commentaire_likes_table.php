<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commentaire_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commentaire_id')->constrained('commentaires')->cascadeOnDelete();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->cascadeOnDelete();
            $table->timestamp('cree_le')->nullable();
            $table->timestamp('modifie_le')->nullable();

            $table->unique(['commentaire_id', 'utilisateur_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commentaire_likes');
    }
};
