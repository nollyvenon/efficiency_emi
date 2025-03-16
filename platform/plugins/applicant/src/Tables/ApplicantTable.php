<?php

namespace Botble\Applicant\Tables;

use Botble\Applicant\Models\Applicant;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\CreatedAtColumn;
use Botble\Table\Columns\FormattedColumn;
use Botble\Table\Columns\IdColumn;
use Botble\Table\HeaderActions\CreateHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class ApplicantTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(Applicant::class)
            ->queryUsing(function (Builder $query) {
                return $query->select([
                    'applicants.*',
                    'users.first_name as user_name',
                    'users.email as user_email',
                    'users.phone as user_phone',
                    'users.country as user_country',
                    'users.occupation as user_occupation'
                ])
                    ->leftJoin('users', 'users.id', '=', 'applicants.user_id')
                    ->with(['programs']); // Ensure eager loading
            })
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
            ->addColumns([
                IdColumn::make(),
                FormattedColumn::make('user_name')
                    ->title(trans('Name'))
                    ->searchable(true),
                FormattedColumn::make('user_email')
                    ->title(trans('Email'))
                    ->searchable(true),
                FormattedColumn::make('user_phone')
                    ->title(trans('Phone'))
                    ->searchable(true),
                FormattedColumn::make('user_country')
                    ->title(trans('Country'))
                    ->searchable(true),
                FormattedColumn::make('user_occupation')
                    ->title(trans('Occupation'))
                    ->searchable(true),
                FormattedColumn::make('programs')
                    ->title(trans('Programs'))
                    ->renderUsing(function ($item) {
                        return optional(optional($item->programs)->pluck('name'))->join(', ') ?? '-';
                    }),
                CreatedAtColumn::make(),
            ]);
    }
}
