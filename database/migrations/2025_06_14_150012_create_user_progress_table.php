<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->integer('current_time')->default(0); // เวลาล่าสุดที่ดูถึง (วินาที)
            $table->boolean('is_completed')->default(false);
            $table->integer('attempt_count')->default(0); // จำนวนครั้งที่พยายามทำข้อสอบ
            $table->timestamp('last_attempt_at')->nullable(); // เวลาที่พยายามครั้งล่าสุด
            $table->timestamps();
            
            $table->unique(['user_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_progress');
    }
}