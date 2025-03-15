<?php

namespace Botble\Applicant\Table\BulkActions;

use Botble\Table\BulkActions\TableBulkActionAbstract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Botble\Program\Models\Program;

class AssignToProgramBulkAction extends TableBulkActionAbstract
{
    public function __construct()
    {
        $this->label = trans('plugins/applicant::applicant.assign_to_program');
    }


    public function handle(Collection $collection, Request $request): \Botble\Base\Http\Responses\BaseHttpResponse
    {
        $program = Program::findOrFail($request->input('program_id'));

        foreach ($collection as $applicant) {
            $applicant->programs()->syncWithoutDetaching([$program->id => [
                'assigned_by' => auth()->id()
            ]]);
        }

        return $this->httpResponse()->setMessage(
            trans('plugins/applicant::applicant.assigned_to_program', ['program' => $program->name])
        );
    }

    public function form(): string
    {
        $programs = Program::pluck('name', 'id');

        return view('plugins/applicant::bulk-actions.assign-to-program-form', compact('programs'));
    }
}
