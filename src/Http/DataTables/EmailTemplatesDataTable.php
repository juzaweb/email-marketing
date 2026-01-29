<?php

namespace Juzaweb\Modules\EmailMarketing\Http\DataTables;

use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Admin\DataTables\Action;
use Juzaweb\Modules\Admin\DataTables\BulkAction;
use Juzaweb\Modules\Admin\DataTables\Column;
use Juzaweb\Modules\Admin\DataTables\DataTable;
use Illuminate\Database\Eloquent\Builder;
use Juzaweb\Modules\EmailMarketing\Models\EmailTemplate;

class EmailTemplatesDataTable extends DataTable
{
    protected string $actionUrl = 'email-marketing/email-templates/bulk';

    public function query(EmailTemplate $model): Builder
    {
        return $model->newQuery();
    }

    public function getColumns(): array
    {
        return [
			Column::checkbox(),
			Column::id(),
			Column::actions(),
			Column::editLink('name', admin_url('email-templates/{id}/edit'), __('admin::translation.label')),
			Column::createdAt()
		];
    }

    public function actions(Model $model): array
    {
        return [
            Action::edit(admin_url("email-templates/{$model->id}/edit"))->can('email-templates.edit'),
            Action::delete()->can('email-templates.delete'),
        ];
    }

    public function bulkActions(): array
    {
        return [
            BulkAction::delete()->can('email-templates.delete'),
        ];
    }
}
