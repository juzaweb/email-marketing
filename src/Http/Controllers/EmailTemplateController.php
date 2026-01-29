<?php

namespace Juzaweb\Modules\EmailMarketing\Http\Controllers;

use Juzaweb\Modules\Admin\Facades\Breadcrumb;
use Juzaweb\Modules\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\EmailMarketing\Models\EmailTemplate;
use Juzaweb\Modules\EmailMarketing\Http\Requests\EmailTemplateRequest;
use Juzaweb\Modules\EmailMarketing\Http\Requests\EmailTemplateActionsRequest;
use Juzaweb\Modules\EmailMarketing\Http\DataTables\EmailTemplatesDataTable;

class EmailTemplateController extends AdminController
{
    public function index(EmailTemplatesDataTable $dataTable, string $websiteId)
    {
        Breadcrumb::add(__('Email Templates'));

        $createUrl = action([static::class, 'create'], [$websiteId]);

        return $dataTable->render(
            'email-marketing::email-template.index',
            compact('createUrl')
        );
    }

    public function create(string $websiteId)
    {
        Breadcrumb::add(__('Email Templates'), admin_url('emailtemplates'));

        Breadcrumb::add(__('Create Email Template'));

        $backUrl = action([static::class, 'index'], [$websiteId]);

        return view(
            'email-marketing::email-template.form',
            [
                'model' => new EmailTemplate(),
                'action' => action([static::class, 'store'], [$websiteId]),
                'backUrl' => $backUrl,
            ]
        );
    }

    public function edit(string $websiteId, string $id)
    {
        Breadcrumb::add(__('Email Templates'), admin_url('emailtemplates'));

        Breadcrumb::add(__('Create Email Templates'));

        $model = EmailTemplate::findOrFail($id);
        $backUrl = action([static::class, 'index'], [$websiteId]);

        return view(
            'email-marketing::email-template.form',
            [
                'action' => action([static::class, 'update'], [$websiteId, $id]),
                'model' => $model,
                'backUrl' => $backUrl,
            ]
        );
    }

    public function store(EmailTemplateRequest $request, string $websiteId)
    {
        $model = DB::transaction(
            function () use ($request) {
                $data = $request->validated();

                return EmailTemplate::create($data);
            }
        );

        return $this->success([
            'redirect' => action([static::class, 'index'], [$websiteId]),
            'message' => __('EmailTemplate :name created successfully', ['name' => $model->name]),
        ]);
    }

    public function update(EmailTemplateRequest $request, string $websiteId, string $id)
    {
        $model = EmailTemplate::findOrFail($id);

        $model = DB::transaction(
            function () use ($request, $model) {
                $data = $request->validated();

                $model->update($data);

                return $model;
            }
        );

        return $this->success([
            'redirect' => action([static::class, 'index'], [$websiteId]),
            'message' => __('EmailTemplate :name updated successfully', ['name' => $model->name]),
        ]);
    }

    public function bulk(EmailTemplateActionsRequest $request, string $websiteId)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        $models = EmailTemplate::whereIn('id', $ids)->get();

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
