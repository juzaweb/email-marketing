<?php

namespace Juzaweb\Modules\EmailMarketing\Http\Controllers;

use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\EmailMarketing\Contracts\AutomationTriggerRegistryInterface;
use Juzaweb\Modules\EmailMarketing\Http\DataTables\AutomationRulesDataTable;
use Juzaweb\Modules\EmailMarketing\Http\Requests\AutomationRuleRequest;
use Juzaweb\Modules\EmailMarketing\Models\AutomationRule;
use Juzaweb\Modules\EmailMarketing\Models\EmailTemplate;
use Illuminate\Http\Request;

class AutomationController extends AdminController
{
    public function index(AutomationRulesDataTable $dataTable)
    {
        Breadcrumb::add(__('email-marketing::translation.email_marketing'), admin_url('email-marketing'));
        Breadcrumb::add(__('email-marketing::translation.automation.title'));

        $createUrl = action([static::class, 'create']);

        return $dataTable->render(
            'email-marketing::automation.index',
            compact('createUrl')
        );
    }

    public function create(AutomationTriggerRegistryInterface $triggerRegistry)
    {
        Breadcrumb::add(__('email-marketing::translation.email_marketing'), admin_url('email-marketing'));
        Breadcrumb::add(__('email-marketing::translation.automation.title'), action([static::class, 'index']));
        Breadcrumb::add(__('admin::translation.create'));

        $triggers = $triggerRegistry->all();
        $templates = EmailTemplate::pluck('name', 'id')->toArray();
        $backUrl = action([static::class, 'index']);

        return view('email-marketing::automation.form', [
            'model' => new AutomationRule(),
            'action' => action([static::class, 'store']),
            'triggers' => $triggers,
            'templates' => $templates,
            'backUrl' => $backUrl,
        ]);
    }

    public function store(AutomationRuleRequest $request)
    {
        $data = $request->validated();

        $model = AutomationRule::create($data);

        return $this->success([
            'redirect' => action([static::class, 'index']),
            'message' => __('admin::translation.created_successfully'),
        ]);
    }

    public function edit(string $id, AutomationTriggerRegistryInterface $triggerRegistry)
    {
        $model = AutomationRule::findOrFail($id);

        Breadcrumb::add(__('email-marketing::translation.email_marketing'), admin_url('email-marketing'));
        Breadcrumb::add(__('email-marketing::translation.automation.title'), action([static::class, 'index']));
        Breadcrumb::add(__('admin::translation.edit'));

        $triggers = $triggerRegistry->all();
        $templates = EmailTemplate::pluck('name', 'id')->toArray();
        $backUrl = action([static::class, 'index']);

        return view('email-marketing::automation.form', [
            'model' => $model,
            'action' => action([static::class, 'update'], [$id]),
            'triggers' => $triggers,
            'templates' => $templates,
            'backUrl' => $backUrl,
        ]);
    }

    public function update(AutomationRuleRequest $request, string $id)
    {
        $model = AutomationRule::findOrFail($id);

        $data = $request->validated();
        $model->update($data);

        return $this->success([
            'redirect' => action([static::class, 'index']),
            'message' => __('admin::translation.updated_successfully'),
        ]);
    }

    public function bulk(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        $models = AutomationRule::whereIn('id', $ids)->get();

        foreach ($models as $model) {
            if ($action === 'delete') {
                $model->delete();
            }
        }

        return $this->success([
            'message' => __('admin::translation.successfully'),
        ]);
    }
}
