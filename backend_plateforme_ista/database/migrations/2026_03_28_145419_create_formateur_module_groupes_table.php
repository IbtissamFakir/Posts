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
        Schema::create('formateur_module_groupes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('module_id')
                ->constrained('modules')
                ->onDelete('cascade');
            $table->foreignId('groupe_id')
                ->constrained('groupes')
                ->onDelete('cascade');
            $table->timestamps();

            // Assurer l'unicité de la combinaison user_id, module_id et groupe_id
            $table->unique(['user_id', 'module_id', 'groupe_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formateur_module_groupes');
    }
};
