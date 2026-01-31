<?php

use Juzaweb\Modules\EmailMarketing\Http\Controllers\SegmentController;
use Juzaweb\Modules\EmailMarketing\Http\Controllers\EmailTemplateController;
use Juzaweb\Modules\EmailMarketing\Http\Controllers\SubscriberController;
use Juzaweb\Modules\EmailMarketing\Http\Controllers\CampaignController;
use Juzaweb\Modules\EmailMarketing\Http\Controllers\AutomationController;

Route::admin('email-marketing/segments', SegmentController::class);
Route::admin('email-marketing/email-templates', EmailTemplateController::class);
Route::admin('email-marketing/subscribers', SubscriberController::class);
Route::get('email-marketing/campaigns/{id}/send', [CampaignController::class, 'send'])->name('email-marketing.campaigns.send');
Route::admin('email-marketing/campaigns', CampaignController::class);
Route::admin('email-marketing/automation', AutomationController::class);
