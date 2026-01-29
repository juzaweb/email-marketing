<?php

namespace Juzaweb\Modules\EmailMarketing\Http\DataTables;

use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Admin\DataTables\Action;
use Juzaweb\Modules\Admin\DataTables\BulkAction;
use Juzaweb\Modules\Admin\DataTables\Column;
use Juzaweb\Modules\Admin\DataTables\DataTable;
use Illuminate\Database\Eloquent\Builder;
use Juzaweb\Modules\EmailMarketing\Models\Subscriber;

class SubscribersDataTable extends DataTable
{
    protected string $actionUrl = 'subscribers/bulk';

    public function query(Subscriber $model): Builder
    {
        return $model->newQuery();
    }

    public function getColumns(): array
    {
        return [
			Column::checkbox(),
			Column::id(),
			Column::actions(),
			Column::make('email'),
			Column::editLink('name', admin_url('subscribers/{id}/edit'), __('admin::translation.label')),
			Column::make('status'),
			Column::createdAt()
		];
    }

    public function actions(Model $model): array
    {
        return [
            Action::edit(admin_url("subscribers/{$model->id}/edit"))->can('subscribers.edit'),
            Action::delete()->can('subscribers.delete'),
        ];
    }

    public function bulkActions(): array
    {
        return [
            BulkAction::delete()->can('subscribers.delete'),
        ];
    }
}
