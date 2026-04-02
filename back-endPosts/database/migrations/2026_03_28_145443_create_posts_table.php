<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('titre'); // Ajouté pour le titre
            $table->text('content');
            $table->string('image')->nullable(); // Ajouté pour les photos
            $table->dateTime('date_publication')->nullable(); // Changé en dateTime pour plus de précision

            // Un seul champ pour le statut (en utilisant enum)
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
//    public function up()
//    {
//        Schema::create('posts', function (Blueprint $table) {
//            $table->id();
//            $table->text('content');
//            $table->date('date_publication');
//            $table->integer('likes')->unsigned()->default(0);
//            $table->foreignId('utilisateur_id')
//                ->constrained('utilisateurs')
//                ->onDelete('cascade');
//            $table->enum('status', ['pending', 'validated', 'rejected'])
//                ->default('pending');
//            $table->foreignId('validated_by')
//                ->nullable()
//                ->constrained('utilisateurs')
//                ->onDelete('set null');
//            $table->foreignId('rejected_by')
//                ->nullable()
//                ->constrained('utilisateurs')
//                ->onDelete('set null');
//            $table->timestamps();
//        });
//    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
