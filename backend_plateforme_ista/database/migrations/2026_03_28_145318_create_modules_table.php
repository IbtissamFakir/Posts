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

        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 255);
            $table->string('code', 50);
            $table->integer('nbrCC')->unsigned();
            $table->integer('coeficient')->unsigned();
            $table->integer('masse_horaire')->unsigned();
            $table->string('type_module', 50);
            $table->foreignId('filiere_id')
                ->constrained('filieres')
                ->onDelete('cascade');
            $table->timestamps();

            $table->unique(["nom", "filiere_id"]); // nom unique par filière
            $table->unique(["code", "filiere_id"]); // code unique par filière
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
};
