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
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->date('date_publication');
            $table->integer('likes')->unsigned()->default(0);
            $table->enum('status', ['visible', 'masque'])
                ->default('visible');
            $table->foreignId('utilisateur_id')
                ->constrained('utilisateurs')
                ->onDelete('cascade');
            $table->foreignId('post_id')
                ->constrained('posts')
                ->onDelete('cascade');
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
        Schema::dropIfExists('commentaires');
    }
};
