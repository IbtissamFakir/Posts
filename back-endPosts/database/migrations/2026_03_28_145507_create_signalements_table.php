<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('signalements', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->foreignId('commentaire_id')
                ->constrained('commentaires')
                ->onDelete('cascade');
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->timestamps();

            // Empêcher qu'un utilisateur signale plusieurs fois le même commentaire
            $table->unique(['commentaire_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('signalements');
    }
};
