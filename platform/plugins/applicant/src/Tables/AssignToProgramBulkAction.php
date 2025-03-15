<?php

class AssignToProgramBulkAction extends BulkAction
{
    public function handle(Collection $items, Request $request)
    {
        $program = Program::findOrFail($request->input('program_id'));

        $items->each(function ($applicant) use ($program) {
            $program->applicants()->syncWithoutDetaching([$applicant->id => [
                'assigned_by' => auth()->id()
            ]]);
        });

        return $this->response()->setMessage("Applicants assigned to {$program->name}");
    }

    public function form()
    {
        $programs = Program::pluck('name', 'id');

        return $this->core()->form()
            ->select('program_id', 'Select Program')
            ->choices($programs)
            ->required();
    }
}
