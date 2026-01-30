<?php

namespace Juzaweb\Modules\EmailMarketing\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Juzaweb\Modules\EmailMarketing\Models\CampaignTracking;

class TrackCampaignActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected array $data
    ) {}

    public function handle(): void
    {
        CampaignTracking::create($this->data);
    }
}
