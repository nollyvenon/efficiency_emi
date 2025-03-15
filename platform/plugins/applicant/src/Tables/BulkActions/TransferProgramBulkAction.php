<?php

namespace Botble\Applicant\Tables\BulkActions;

use Botble\Table\BulkActions\AbstractBulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Botble\Program\Models\Program;

class TransferProgramBulkAction extends AbstractBulkAction
{
    public function __construct()
    {
        $this->label = trans('plugins/applicant::applicant.transfer_to_program');
    }

    public function handle(Collection $collection, Request $request): \Botble\Base\Http\Responses\BaseHttpResponse
    {
        $fromProgram = Program::findOrFail($request->input('from_program_id'));
        $toProgram = Program::findOrFail($request->input('to_program_id'));

        foreach ($collection as $applicant) {
            if ($applicant->programs()->where('program_id', $fromProgram->id)->exists()) {
                $applicant->programs()->updateExistingPivot($fromProgram->id, [
                    'deleted_at' => now()
                ]);

                $applicant->programs()->attach($toProgram->id, [
                    'assigned_by' => auth()->id(),
                    'transferred_at' => now()
                ]);
            }
        }

        return $this->httpResponse()->setMessage(
            trans('plugins/applicant::applicant.transferred_to_program', [
                'count' => $collection->count(),
                'from' => $fromProgram->name,
                'to' => $toProgram->name
            ])
        );
    }

    public function form(): string
    {
        $programs = Program::pluck('name', 'id');

        return view('plugins/applicant::bulk-actions.transfer-program-form', compact('programs'));
    }
}
