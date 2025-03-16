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
                $table->string('photo');
                $table->text('description')->nullable();
                $table->string('slug')->nullable();
                $table->dateTime('start_time');
                $table->dateTime('end_time');
                $table->string('location_address', 255)->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->string('status', 60)->default('published');
                $table->timestamps();
            });
        }

        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('user_id');
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
        Schema::dropIfExists('event_registrations');
    }
};
