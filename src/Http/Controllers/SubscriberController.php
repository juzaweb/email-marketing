<?php

namespace Juzaweb\Modules\EmailMarketing\Http\Controllers;

use Juzaweb\Modules\Admin\Facades\Breadcrumb;
use Juzaweb\Modules\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\EmailMarketing\Models\Subscriber;
use Juzaweb\Modules\EmailMarketing\Http\Requests\SubscriberRequest;
use Juzaweb\Modules\EmailMarketing\Http\Requests\SubscriberActionsRequest;
use Juzaweb\Modules\EmailMarketing\Http\DataTables\SubscribersDataTable;

class SubscriberController extends AdminController
{
    public function index(SubscribersDataTable $dataTable, string $websiteId)
    {
        Breadcrumb::add(__('Subscribers'));

        $createUrl = action([static::class, 'create'], [$websiteId]);

        return $dataTable->render(
            'email-marketing::subscriber.index',
            compact('createUrl')
        );
    }

    public function create(string $websiteId)
    {
        Breadcrumb::add(__('Subscribers'), admin_url('subscribers'));

        Breadcrumb::add(__('Create Subscriber'));

        $backUrl = action([static::class, 'index'], [$websiteId]);

        return view(
            'email-marketing::subscriber.form',
            [
                'model' => new Subscriber(),
                'action' => action([static::class, 'store'], [$websiteId]),
                'backUrl' => $backUrl,
            ]
        );
    }

    public function edit(string $websiteId, string $id)
    {
        Breadcrumb::add(__('Subscribers'), admin_url('subscribers'));

        Breadcrumb::add(__('Create Subscribers'));

        $model = Subscriber::findOrFail($id);
        $backUrl = action([static::class, 'index'], [$websiteId]);

        return view(
            'email-marketing::subscriber.form',
            [
                'action' => action([static::class, 'update'], [$websiteId, $id]),
                'model' => $model,
                'backUrl' => $backUrl,
            ]
        );
    }

    public function store(SubscriberRequest $request, string $websiteId)
    {
        $model = DB::transaction(
            function () use ($request) {
                $data = $request->validated();

                return Subscriber::create($data);
            }
        );

        return $this->success([
            'redirect' => action([static::class, 'index'], [$websiteId]),
            'message' => __('Subscriber :name created successfully', ['name' => $model->name]),
        ]);
    }

    public function update(SubscriberRequest $request, string $websiteId, string $id)
    {
        $model = Subscriber::findOrFail($id);

        $model = DB::transaction(
            function () use ($request, $model) {
                $data = $request->validated();

                $model->update($data);

                return $model;
            }
        );

        return $this->success([
            'redirect' => action([static::class, 'index'], [$websiteId]),
            'message' => __('Subscriber :name updated successfully', ['name' => $model->name]),
        ]);
    }

    public function bulk(SubscriberActionsRequest $request, string $websiteId)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        $models = Subscriber::whereIn('id', $ids)->get();

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
