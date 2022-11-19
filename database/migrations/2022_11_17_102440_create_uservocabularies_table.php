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
        Schema::create('uservocabularies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('language1');
            $table->string('language2');

            $table->string('mnemonics');
            $table->string('mnemoniclist')->nullable();
            $table->string('semanticlist');

            $table->integer('attempts_lang1')->default(0);
            $table->integer('success_lang1')->default(0);
            $table->integer('attempts_lang2')->default(0);
            $table->integer('success_lang2')->default(0);

            $table->string('memorizationLevel')->default('basic1');
            $table->integer('nextReview');
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
        Schema::dropIfExists('uservocabularies');
    }
};
