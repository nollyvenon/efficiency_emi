<?php

// src/Tables/ActivityTable.php
namespace Botble\Applicant\Tables;

use Botble\Applicant\Tables\BulkActions\AssignToProgramBulkAction;
use Botble\Applicant\Tables\BulkActions\TransferProgramBulkAction;
use Botble\Applicant\Tables\BulkActions\RemoveFromProgramBulkAction;
use Botble\Applicant\Models\Applicant;
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
use Botble\Table\Columns\DropdownColumn;

use Closure;

class ApplicantTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Applicant::class)
            ->addHeaderAction(
                    CreateHeaderAction::make()
                        ->route('applicants.create')
            )
            ->addActions([
                EditAction::make()->route('applicants.edit'),
                DeleteAction::make()->route('applicants.destroy')
            ])
            ->addBulkActions([
                DeleteBulkAction::make()->permission('applicants.destroy'),
            ])
            ->addBulkChanges([
                NameBulkChange::make(),
                StatusBulkChange::make(),
                CreatedAtBulkChange::make(),
            ])
            ->addColumns([
                IdColumn::make(),
                Column::make('name')->searchable(),
                Column::make('email')->searchable(),
                Column::make('phone')->searchable(),
                Column::make('country')->searchable(),
                Column::make('occupation')->searchable(),
                Column::make('programs_count')
                    ->title('Programs')
                    ->renderUsing(function ($applicant) {
                        return $applicant->programs->pluck('name')->join(', ');
                    }),
                CreatedAtColumn::make(),
            ]);
    }
}
