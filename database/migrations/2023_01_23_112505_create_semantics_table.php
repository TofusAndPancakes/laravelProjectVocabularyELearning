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
        Schema::create('semantics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->constrained()->onDelete('cascade');
            $table->string('semanticlanguage1');
            $table->string('semanticlanguage2');
            $table->string('semanticdata_id');
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
        Schema::dropIfExists('semantics');
    }
};
