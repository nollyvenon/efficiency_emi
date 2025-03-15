<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255);
                $table->string('status', 60)->default('published');
                $table->timestamps();
            });
        }

        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->timestamps();
        });

        if (! Schema::hasTable('events_translations')) {
            Schema::create('events_translations', function (Blueprint $table) {
                $table->string('lang_code');
                $table->foreignId('events_id');
                $table->string('name', 255)->nullable();

                $table->primary(['lang_code', 'events_id'], 'events_translations_primary');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('events_translations');
    }
};
