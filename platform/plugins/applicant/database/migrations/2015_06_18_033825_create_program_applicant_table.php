<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramApplicantTable extends Migration
{
    public function up()
    {
        Schema::create('applicant_program_pivot', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('applicant_program', function (Blueprint $table) {
            $table->foreignId('applicant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->primary(['applicant_id', 'program_id']);
        });


    }

    public function down()
    {
        Schema::dropIfExists('applicant_program_pivot');
        Schema::dropIfExists('program_applicant');
    }
}
