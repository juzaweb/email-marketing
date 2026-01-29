<?php

namespace Juzaweb\Modules\EmailMarketing\Http\DataTables;

use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Core\DataTables\Action;
use Juzaweb\Modules\Core\DataTables\BulkAction;
use Juzaweb\Modules\Core\DataTables\Column;
use Juzaweb\Modules\Core\DataTables\DataTable;
use Illuminate\Database\Eloquent\Builder;
use Juzaweb\Modules\EmailMarketing\Models\Campaign;

class CampaignsDataTable extends DataTable
{
    protected string $actionUrl = 'campaigns/bulk';

    public function query(Campaign $model): Builder
    {
        return $model->newQuery();
    }

    public function getColumns(): array
    {
        return [
            Column::checkbox(),
            Column::id(),
            Column::actions(),
            Column::editLink('name', admin_url('email-marketing/campaigns/{id}/edit'), __('core::translation.label')),
            Column::make('subject'),
            Column::make('template_id'),
            Column::make('status'),
            Column::make('sent_at'),
            Column::createdAt()
        ];
    }

    public function actions(Model $model): array
    {
        return [
            Action::edit(admin_url("email-marketing/campaigns/{$model->id}/edit"))->can('campaigns.edit'),
            Action::delete()->can('campaigns.delete'),
        ];
    }

    public function bulkActions(): array
    {
        return [
            BulkAction::delete()->can('campaigns.delete'),
        ];
    }
}
