<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Services\KenaikanKelasService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    app(KenaikanKelasService::class)->prosesKenaikanOtomatis();
})->daily()->name('kenaikan-kelas-otomatis')->withoutOverlapping();