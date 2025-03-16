<?php

namespace Botble\Event\Tables;

use Botble\Event\Models\Event;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class EventTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Event::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('event.create'))
            ->addActions([
                EditAction::make()->route('event.edit'),
                DeleteAction::make()->route('event.destroy'),
            ])
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('event.edit'),
                ImageColumn::make('photo')
                    ->title(trans('Photo'))
                    ->width(80)
                    ->alignLeft()
                    ->orderable(false),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('event.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->queryUsing(function (Builder $query) {
                $query->select([
                    'id',
                    'name',
                    'photo',
                    'created_at',
                    'status',
                ]);
            });
    }
}
