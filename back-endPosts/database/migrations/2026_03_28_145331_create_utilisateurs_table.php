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
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom_complet', 255);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('role', 100);
            $table->string('statut', 100);
            $table->string('CEF_matricule', 100);
            $table->string('statut', 100);
            $table->string('photo', 255)->nullable();
            $table->foreignId("groupe_id")
                ->constrained("groupes")
                ->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('utilisateurs');
    }
};
