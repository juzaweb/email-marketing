<?php

namespace Juzaweb\Modules\EmailMarketing\Http\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Core\DataTables\Action;
use Juzaweb\Modules\Core\DataTables\BulkAction;
use Juzaweb\Modules\Core\DataTables\Column;
use Juzaweb\Modules\Core\DataTables\DataTable;
use Juzaweb\Modules\EmailMarketing\Models\AutomationRule;

class AutomationRulesDataTable extends DataTable
{
    protected string $actionUrl = 'email-marketing/automation/bulk';

    public function query(AutomationRule $model): Builder
    {
        return $model->newQuery();
    }

    public function getColumns(): array
    {
        return [
            Column::checkbox(),
            Column::id(),
            Column::actions(),
            Column::editLink('name', admin_url('email-marketing/automation/{id}/edit'), __('admin::translation.name')),
            Column::make('trigger_type', function ($row) {
                return $row->trigger_type;
            }, __('email-marketing::translation.automation.trigger.label')),
            Column::make('active', function ($row) {
                return $row->active ? __('admin::translation.active') : __('admin::translation.inactive');
            }, __('admin::translation.status')),
            Column::createdAt()
        ];
    }

    public function actions(Model $model): array
    {
        return [
            Action::edit(admin_url("email-marketing/automation/{$model->id}/edit")),
            Action::delete(),
        ];
    }

    public function bulkActions(): array
    {
        return [
            BulkAction::delete(),
        ];
    }
}
