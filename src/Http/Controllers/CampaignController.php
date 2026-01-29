<?php

namespace Juzaweb\Modules\EmailMarketing\Http\Controllers;

use Juzaweb\Modules\Admin\Facades\Breadcrumb;
use Juzaweb\Modules\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\EmailMarketing\Models\Campaign;
use Juzaweb\Modules\EmailMarketing\Models\Segment;
use Juzaweb\Modules\EmailMarketing\Http\Requests\CampaignRequest;
use Juzaweb\Modules\EmailMarketing\Http\Requests\CampaignActionsRequest;
use Juzaweb\Modules\EmailMarketing\Http\DataTables\CampaignsDataTable;

class CampaignController extends AdminController
{
    public function index(CampaignsDataTable $dataTable, string $websiteId)
    {
        Breadcrumb::add(__('Campaigns'));

        $createUrl = action([static::class, 'create'], [$websiteId]);

        return $dataTable->render(
            'email-marketing::campaign.index',
            compact('createUrl')
        );
    }

    public function create(string $websiteId)
    {
        Breadcrumb::add(__('Campaigns'), admin_url('campaigns'));

        Breadcrumb::add(__('Create Campaign'));

        $backUrl = action([static::class, 'index'], [$websiteId]);
        $segments = Segment::all()->pluck('name', 'id')->toArray();

        return view(
            'email-marketing::campaign.form',
            [
                'model' => new Campaign(),
                'action' => action([static::class, 'store'], [$websiteId]),
                'backUrl' => $backUrl,
                'segments' => $segments,
            ]
        );
    }

    public function edit(string $websiteId, string $id)
    {
        Breadcrumb::add(__('Campaigns'), admin_url('campaigns'));

        Breadcrumb::add(__('Create Campaigns'));

        $model = Campaign::with('segments')->findOrFail($id);
        $backUrl = action([static::class, 'index'], [$websiteId]);
        $segments = Segment::all()->pluck('name', 'id')->toArray();

        return view(
            'email-marketing::campaign.form',
            [
                'action' => action([static::class, 'update'], [$websiteId, $id]),
                'model' => $model,
                'backUrl' => $backUrl,
                'segments' => $segments,
            ]
        );
    }

    public function store(CampaignRequest $request, string $websiteId)
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
            'redirect' => action([static::class, 'index'], [$websiteId]),
            'message' => __('Campaign :name created successfully', ['name' => $model->name]),
        ]);
    }

    public function update(CampaignRequest $request, string $websiteId, string $id)
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
            'redirect' => action([static::class, 'index'], [$websiteId]),
            'message' => __('Campaign :name updated successfully', ['name' => $model->name]),
        ]);
    }

    public function bulk(CampaignActionsRequest $request, string $websiteId)
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
