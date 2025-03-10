<?php
// platform/plugins/program/src/Tables/ProgramTable.php

namespace Botble\Program\Tables;

use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;
use Botble\Program\Models\Program;

class ProgramTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Program::class)
            ->addHeaderAction(CreateHeaderAction::make()->route('program.create'))
            ->addActions([
                EditAction::make()->route('program.edit'),
                DeleteAction::make()->route('program.destroy'),
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('program.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->addColumns([
                IdColumn::make(),
                NameColumn::make()->route('program.edit'),
                ImageColumn::make('photo')
                    ->title(trans('Photo'))
                    ->alignLeft(),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->queryUsing(fn (Builder $query) => $query->select([
                'id',
                'name',
                'photo',
                //'start_date',
                //'end_date',
                //'location',
                //'slug',
                //'socials',
                'created_at',
                'status',
            ]));
    }
}
