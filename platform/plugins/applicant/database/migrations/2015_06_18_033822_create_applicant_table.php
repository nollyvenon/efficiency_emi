<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantsTable extends Migration
{
    public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('user_id', 255);
            $table->string('highest_degree')->unique();
            $table->string('course_studied')->unique();
            $table->string('birth_date')->unique();
            $table->string('passport')->nullable();
            $table->text('additional_info')->nullable();
            $table->text('resume')->nullable();
            $table->enum('status', ['pending', 'selected', 'rejected'])->default('pending');
            $table->unsignedBigInteger('program_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('applicants');
    }
}
