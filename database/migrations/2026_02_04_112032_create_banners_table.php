<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', static function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')
                ->default(true)
                ->unsigned()
                ->index();
            $table->boolean('star')
                ->default(false)
                ->unsigned()
                ->index();
            $table->string('location', 150)
                ->nullable();
            $table->string('name');
            $table->json('meta')
                ->nullable();
            $table->text('link')
                ->nullable();
            $table->string('target', 30)
                ->nullable();
            $table->timestamp('published_at')
                ->nullable();
            $table->timestamp('until_then')
                ->nullable();
            $table->text('desktop')
                ->nullable();
            $table->text('notebook')
                ->nullable();
            $table->text('mobile')
                ->nullable();
            $table->text('video')
                ->nullable();
            $table->string('slug')
                ->unique()
                ->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
