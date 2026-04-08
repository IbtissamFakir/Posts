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
        Schema::create('enregistrements', function (Blueprint $table) {
            $table->id();

            // the annonce being enregistré

            $table->foreignId('annonce_id')->nullable()
                ->constrained('annonces')
                ->onDelete('cascade');


            // The post being enregistré
            $table->foreignId('post_id')->nullable()
                ->constrained('posts')
                ->onDelete('cascade');

            // The user who made the enregistrement
            $table->foreignId('user_id')
                ->constrained('users')
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
        Schema::dropIfExists('enregistrements');
    }
};
