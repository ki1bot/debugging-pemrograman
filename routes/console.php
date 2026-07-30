<?php

use Database\Seeders\AdditionalProgrammingLanguageSeeder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('challenges:sync', function () {
    $exitCode = $this->call('db:seed', [
        '--class' => AdditionalProgrammingLanguageSeeder::class,
        '--force' => true,
    ]);

    if ($exitCode !== 0) {
        return $exitCode;
    }

    $this->info('Kategori dan tantangan bahasa tambahan berhasil disinkronkan.');

    return 0;
})->purpose('Sinkronkan kategori dan tantangan bahasa pemrograman tambahan');
