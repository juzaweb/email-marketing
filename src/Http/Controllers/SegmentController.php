<?php

namespace Juzaweb\Modules\EmailMarketing\Http\Controllers;

use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\EmailMarketing\Models\Segment;
use Juzaweb\Modules\EmailMarketing\Http\Requests\SegmentRequest;
use Juzaweb\Modules\EmailMarketing\Http\Requests\SegmentActionsRequest;
use Juzaweb\Modules\EmailMarketing\Http\DataTables\SegmentsDataTable;

class SegmentController extends AdminController
{
    public function index(SegmentsDataTable $dataTable)
    {
        Breadcrumb::add(__('Segments'));

        $createUrl = action([static::class, 'create']);

        return $dataTable->render(
            'email-marketing::segment.index',
            compact('createUrl')
        );
    }

    public function create()
    {
        Breadcrumb::add(__('Segments'), admin_url('segments'));

        Breadcrumb::add(__('Create Segment'));

        $backUrl = action([static::class, 'index']);

        return view(
            'email-marketing::segment.form',
            [
                'model' => new Segment(),
                'action' => action([static::class, 'store']),
                'backUrl' => $backUrl,
            ]
        );
    }

    public function edit(string $id)
    {
        Breadcrumb::add(__('Segments'), admin_url('segments'));

        Breadcrumb::add(__('Create Segments'));

        $model = Segment::findOrFail($id);
        $backUrl = action([static::class, 'index']);

        return view(
            'email-marketing::segment.form',
            [
                'action' => action([static::class, 'update'], [$id]),
                'model' => $model,
                'backUrl' => $backUrl,
            ]
        );
    }

    public function store(SegmentRequest $request)
    {
        $model = DB::transaction(
            function () use ($request) {
                $data = $request->validated();

                return Segment::create($data);
            }
        );

        return $this->success([
            'redirect' => action([static::class, 'index']),
            'message' => __('Segment :name created successfully', ['name' => $model->name]),
        ]);
    }

    public function update(SegmentRequest $request, string $id)
    {
        $model = Segment::findOrFail($id);

        $model = DB::transaction(
            function () use ($request, $model) {
                $data = $request->validated();

                $model->update($data);

                return $model;
            }
        );

        return $this->success([
            'redirect' => action([static::class, 'index']),
            'message' => __('Segment :name updated successfully', ['name' => $model->name]),
        ]);
    }

    public function bulk(SegmentActionsRequest $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        $models = Segment::whereIn('id', $ids)->get();

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
