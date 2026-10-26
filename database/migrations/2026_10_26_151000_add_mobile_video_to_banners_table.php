<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', static function (Blueprint $table): void {
            $table->text('mobile_video')
                ->nullable()
                ->after('video');
        });
    }

    public function down(): void
    {
        Schema::table('banners', static function (Blueprint $table): void {
            $table->dropColumn('mobile_video');
        });
    }
};
