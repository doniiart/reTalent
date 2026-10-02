<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Perintah Artisan CLI kustom — menampilkan kata motivasi
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
