<?php

namespace Juzaweb\Modules\EmailMarketing\Http\Controllers;

use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\EmailMarketing\Enums\CampaignStatusEnum;
use Juzaweb\Modules\EmailMarketing\Models\Campaign;
use Juzaweb\Modules\EmailMarketing\Models\Segment;
use Juzaweb\Modules\EmailMarketing\Http\Requests\CampaignRequest;
use Juzaweb\Modules\EmailMarketing\Http\Requests\CampaignActionsRequest;
use Juzaweb\Modules\EmailMarketing\Http\DataTables\CampaignsDataTable;
use Juzaweb\Modules\EmailMarketing\Services\CampaignService;

class CampaignController extends AdminController
{
    public function index(CampaignsDataTable $dataTable)
    {
        Breadcrumb::add(__('Campaigns'));

        $createUrl = action([static::class, 'create']);

        return $dataTable->render(
            'email-marketing::campaign.index',
            compact('createUrl')
        );
    }

    public function create()
    {
        Breadcrumb::add(__('Campaigns'), admin_url('campaigns'));

        Breadcrumb::add(__('Create Campaign'));

        $backUrl = action([static::class, 'index']);
        $segments = Segment::pluck('name', 'id')->toArray();

        return view(
            'email-marketing::campaign.form',
            [
                'model' => new Campaign(),
                'action' => action([static::class, 'store']),
                'backUrl' => $backUrl,
                'segments' => $segments,
            ]
        );
    }

    public function edit(string $id)
    {
        Breadcrumb::add(__('Campaigns'), admin_url('campaigns'));

        Breadcrumb::add(__('Create Campaigns'));

        $model = Campaign::with('segments')->findOrFail($id);
        $backUrl = action([static::class, 'index']);
        $segments = Segment::pluck('name', 'id')->toArray();

        return view(
            'email-marketing::campaign.form',
            [
                'action' => action([static::class, 'update'], [$id]),
                'model' => $model,
                'backUrl' => $backUrl,
                'segments' => $segments,
            ]
        );
    }

    public function store(CampaignRequest $request)
    {
        $model = DB::transaction(
            function () use ($request) {
                $data = $request->validated();
                $segmentIds = $data['segment_ids'] ?? [];
                unset($data['segment_ids']);

                $model = Campaign::create($data);
                $model->segments()->sync($segmentIds);

                return $model;
            }
        );

        return $this->success([
            'redirect' => action([static::class, 'index']),
            'message' => __('Campaign :name created successfully', ['name' => $model->name]),
        ]);
    }

    public function update(CampaignRequest $request, string $id)
    {
        $model = Campaign::findOrFail($id);

        $model = DB::transaction(
            function () use ($request, $model) {
                $data = $request->validated();
                $segmentIds = $data['segment_ids'] ?? [];
                unset($data['segment_ids']);

                $model->update($data);
                $model->segments()->sync($segmentIds);

                return $model;
            }
        );

        return $this->success([
            'redirect' => action([static::class, 'index']),
            'message' => __('Campaign :name updated successfully', ['name' => $model->name]),
        ]);
    }

    public function send(CampaignService $service, string $id)
    {
        $campaign = Campaign::findOrFail($id);

        if ($campaign->status !== CampaignStatusEnum::DRAFT) {
            return $this->error([
                'message' => __('Campaign is not in draft status.'),
            ]);
        }

        $result = $service->execute($campaign);

        if (is_array($result)) {
            if (!$result['status']) {
                return $this->error([
                    'message' => $result['message'],
                ]);
            }
        } else {
            if (!$result->status) {
                return $this->error([
                    'message' => $result->message,
                ]);
            }
        }

        return $this->success([
            'message' => __('Campaign sending started successfully.'),
            'redirect' => action([static::class, 'index']),
        ]);
    }

    public function bulk(CampaignActionsRequest $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        $models = Campaign::whereIn('id', $ids)->get();

        foreach ($models as $model) {
            if ($action === 'activate') {
                $model->update(['active' => true]);
            }

            if ($action === 'deactivate') {
                $model->update(['active' => false]);
            }

            if ($action === 'delete') {
                $model->delete();
            }
        }

        return $this->success([
            'message' => __('Bulk action performed successfully'),
        ]);
    }
}
