<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('voice_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('original_text');
            $table->string('source_language', 10)->default('en');
            $table->string('target_language', 10);
            $table->text('translated_text')->nullable();
            $table->string('voice_type')->default('female');
            $table->decimal('pitch', 3, 1)->default(1.0);
            $table->decimal('speed', 3, 1)->default(1.0);
            $table->string('audio_file_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('voice_history');
    }
};