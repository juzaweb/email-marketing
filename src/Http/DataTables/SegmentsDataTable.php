<?php

namespace Juzaweb\Modules\EmailMarketing\Http\DataTables;

use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Core\DataTables\Action;
use Juzaweb\Modules\Core\DataTables\BulkAction;
use Juzaweb\Modules\Core\DataTables\Column;
use Juzaweb\Modules\Core\DataTables\DataTable;
use Illuminate\Database\Eloquent\Builder;
use Juzaweb\Modules\EmailMarketing\Models\Segment;

class SegmentsDataTable extends DataTable
{
    protected string $actionUrl = 'email-marketing/segments/bulk';

    public function query(Segment $model): Builder
    {
        return $model->newQuery();
    }

    public function getColumns(): array
    {
        return [
            Column::checkbox(),
            Column::id(),
            Column::actions(),
            Column::editLink('name', admin_url('email-marketing/segments/{id}/edit'), __('admin::translation.label')),
            Column::createdAt()
        ];
    }

    public function actions(Model $model): array
    {
        return [
            Action::edit(admin_url("email-marketing/segments/{$model->id}/edit"))->can('segments.edit'),
            Action::delete()->can('segments.delete'),
        ];
    }

    public function bulkActions(): array
    {
        return [
            BulkAction::delete()->can('segments.delete'),
        ];
    }
}
