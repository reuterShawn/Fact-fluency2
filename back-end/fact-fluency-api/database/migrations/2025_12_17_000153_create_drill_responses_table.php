<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('drill_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('drill_sessions')->onDelete('cascade');
            $table->json('problem');
            $table->integer('correct_answer');
            $table->integer('student_answer')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->integer('response_time_ms')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('drill_responses');
    }
};