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

    private string|int|null $programId;

    public function setup(): void
    {
        $this->programId = request()->segment(3);
        $this
            ->model(Activity::class)
            ->addHeaderAction(
                $this->programId ?
                    CreateHeaderAction::make()
                        ->route('admin.programs.activities.create', ['program' => $this->programId])
                    : null
            )
            ->addActions([
                EditAction::make()->route('admin.programs.activities.edit', [
                    'program' => $this->programId,
                    'activity' => 'id' // Botble auto-resolves model ID
                ]),
                DeleteAction::make()->route('admin.programs.activities.destroy', [
                    'program' => $this->programId,
                    'activity' => 'id'
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
                NameColumn::make()
                    ->route('admin.programs.activities.edit', [
                        'program' => 1,
                        'activity' => 'id' // Auto-resolves from Activity model
                    ]),
                CreatedAtColumn::make('start_date'),
                CreatedAtColumn::make('end_date'),
                ImageColumn::make('photo')
                    ->title(trans('Photo'))
                    ->width(80)
                    ->alignLeft()
                    ->orderable(false),
                CreatedAtColumn::make(),
                StatusColumn::make(),
            ])
            ->queryUsing(function ($query) {
                $this->programId = request()->segment(3);
                $query->where('program_id', 1)
                    ->with(['program']);
            });
    }

    protected function getRouteParams(): Closure
    {
        return function ($model = null) {
            $params = ['program' => $this->programId];
            if ($model) $params['activity'] = $model->id;
            return $params;
        };
    }

    public function setProgramId(int $programId): self
    {
        $this->programId = $programId;
        return $this;
    }
}
