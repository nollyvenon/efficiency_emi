<?php
// platform/plugins/program/src/Tables/ProgramTable.php

namespace Botble\Program\Tables;

use Botble\Program\Models\Activity;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\LinkableColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;
use Botble\Program\Models\Program;
use Botble\Base\Facades\Html;
use Illuminate\Support\Facades\Storage;

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
                FormattedColumn::make('activities_count')
                    ->title(trans('plugins/program::activity.menu_name'))
                    ->getValueUsing(function (FormattedColumn $column) {
                        $program = $column->getItem();

                        $color =$program->percentage ? 'text-danger' : 'text-success';

                        //return Html::tag('span', $program->activities_count . ' Activity', ['class' => $color]);
                        return Html::link(route('admin.programs.activities.index', $program->id), sprintf('%s (%s)', 'View Activities', $program->activities_count ), [
                            'data-bs-toggle' => 'tooltip',
                            'data-bs-original-title' => trans('plugins/program::activity.total_posts', ['total' => $program->activities_count]),
                            'target' => '_blank',
                            'class' => $color
                        ]);
                    }),
                ImageColumn::make('photo')
                    ->title(trans('Photo'))
                    ->width(80)
                    ->alignLeft()
                    ->orderable(false),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->queryUsing(fn (Builder $query) => $query
                ->select(['id', 'name', 'photo', 'created_at', 'status'])
                ->withCount('activities')
            );

        /*$program = Program::factory()
            ->has(Activity::factory()->count(5))
            ->create();*/
    }
}
