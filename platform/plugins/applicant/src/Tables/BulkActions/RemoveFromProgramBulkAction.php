<?php

namespace Botble\Applicant\Tables\BulkActions;

use Botble\Table\BulkActions\AbstractBulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Botble\Program\Models\Program;

class RemoveFromProgramBulkAction extends AbstractBulkAction
{
    public function __construct()
    {
        $this->label = trans('plugins/applicant::applicant.remove_from_program');
    }

    public function handle(Collection $collection, Request $request): \Botble\Base\Http\Responses\BaseHttpResponse
    {
        $program = Program::findOrFail($request->input('program_id'));

        foreach ($collection as $applicant) {
            $applicant->programs()->detach($program->id);
        }

        return $this->httpResponse()->setMessage(
            trans('plugins/applicant::applicant.removed_from_program', [
                'count' => $collection->count(),
                'program' => $program->name
            ])
        );
    }

    public function form(): string
    {
        $programs = Program::pluck('name', 'id');

        return view('plugins/applicant::bulk-actions.remove-from-program-form', compact('programs'));
    }
}
