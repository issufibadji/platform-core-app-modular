<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feature_flags', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('organization_id')->nullable()->index();
            $table->string('module', 64)->nullable()->index();
            $table->boolean('is_enabled')->default(false);
            $table->timestamps();

            $table->unique(['key', 'organization_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_flags');
    }
};
