<?php
// src/Tables/ActivityTable.php
namespace Botble\Program\Tables;

use Botble\Base\Facades\Html;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\BulkChanges\CreatedAtBulkChange;
use Botble\Table\BulkChanges\NameBulkChange;
use Botble\Table\BulkChanges\StatusBulkChange;
use Botble\Table\Columns\Column;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Program\Models\Activity;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\Columns\ImageColumn;
use Botble\Table\Columns\NameColumn;
use Botble\Table\Columns\StatusColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Closure;

class ActivityTable extends TableAbstract
{
    //protected ?int $program = null;

    private string|int|null $programId = null;

    public function setup(): void
    {
        $programId = request()->segment(3);
        $activityId = request()->segment(5);
        $this
            ->model(Activity::class)
            ->addHeaderAction(
                $programId ?
                    CreateHeaderAction::make()
                        ->route('admin.programs.activities.create', ['program' => $programId])
                    : null
            )
            ->addActions([
                EditAction::make()->route('admin.programs.activities.edit', [
                    'program' => $programId,
                    'activity' => "{activity}",
                ]),
                DeleteAction::make()->route('admin.programs.activities.destroy', [
                    'program' => $programId,
                    'activity' => "{activity}",
                ])
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('activity.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->addColumns([
                IdColumn::make(),
                FormattedColumn::make('title')
                    ->title(trans('plugins/program::activity.forms.title'))
                    ->getValueUsing(function (FormattedColumn $column) {
                        $activity = $column->getItem();

                        $color =$activity->percentage ? 'text-danger' : 'text-success';

                        return Html::link(route('admin.programs.activities.edit', [
                            'program' => request()->segment(3),
                            'activity' => $activity->id
                        ]), sprintf('%s', $activity->title ), [
                            'data-bs-toggle' => 'tooltip',
                            'target' => '_blank',
                            'class' => $color
                        ]);
                    }),

                CreatedAtColumn::make('start_time'),
                CreatedAtColumn::make('end_time'),
                ImageColumn::make('photo')
                    ->title(trans('Photo'))
                    ->width(80)
                    ->alignLeft()
                    ->orderable(false),
                NameColumn::make('status'),
            ])
            ->queryUsing(function ($query) {
                $programId = request()->segment(3);
                $query->where('program_id', $programId)
                    ->with(['program']);
            });
    }

    public function setProgramId(int $programId): self
    {
        $this->programId = $programId;
        return $this;
    }
}
