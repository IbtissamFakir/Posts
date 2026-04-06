<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('content');

            $table->json('images')->nullable();
            $table->json('fichiers')->nullable();
            $table->dateTime('date_publication')->nullable();
            // Un seul champ pour le statut
            $table->enum('statut', ['pending', 'validated', 'rejected'])
                ->default('pending');

            // Lien avec l'auteur du post
            $table->foreignId('utilisateur_id')
                ->constrained('utilisateurs')
                ->onDelete('cascade');

            // Liens avec les admins (pour la validation)
            $table->foreignId('validated_by')
                ->nullable()
                ->constrained('utilisateurs')
                ->onDelete('set null');

            $table->foreignId('rejected_by')
                ->nullable()
                ->constrained('utilisateurs')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
