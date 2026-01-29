<?php

use Juzaweb\Modules\EmailMarketing\Http\Controllers\TrackingController;
use Juzaweb\Modules\EmailMarketing\Http\Controllers\UnsubscribeController;

Route::get('/t/o/{campaign_id}/{subscriber_id}.png', [TrackingController::class, 'trackOpen'])
    ->name('track.open');
Route::get('/t/c', [TrackingController::class, 'trackClick'])->name('track.click');
Route::get('/unsubscribe/{subscriber}', [UnsubscribeController::class, 'confirm'])
    ->name('unsubscribe.confirm')
    ->middleware('signed'); // Bảo mật bằng chữ ký số

Route::post('/unsubscribe/{subscriber}', [UnsubscribeController::class, 'process'])
    ->name('unsubscribe.process');
