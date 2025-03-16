<?php

namespace Botble\Program\Http\Controllers;

use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Program\Forms\ActivityForm;
use Botble\Program\Forms\ProgramForm;
use Botble\Program\Models\Activity;
use Botble\Program\Models\Program;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Program\Tables\ActivityTable;
use Illuminate\Http\Request;

class ActivityController extends BaseController
{
    public function index(Program $program, ActivityTable $table)
    {
        $programId = intval(request()->segment(3));
        PageTitle::setTitle("Activities for {$program->name}");
        return $table
            ->setProgramId($programId) // Must be called first
            ->renderTable();
    }

    public function create(Program $program)
    {
        //return view('plugins/program::admin.activities.create', compact('program'));
        PageTitle::setTitle(trans('plugins/program::activity.create'));
        return ActivityForm::create()
            ->setUrl(route('programs.activities.store', $program->id))
            ->renderForm();

    }

    public function store(Request $request, Program $program, BaseHttpResponse $response)
    {
        // Add validation rules
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => ['nullable', 'string', 'max:10000'],
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'photo' => ['nullable', 'string', 'max:255'],
        ]);

        $activity = $program->activities()->create($validated);

        return $response
            ->setPreviousUrl(route('admin.programs.activities.index', $program->id))
            ->setNextUrl(route('admin.programs.activities.edit', [$program->id, $activity->id]))
            ->setMessage('Activity created successfully');
    }


    public function edit(Program $program, Activity $activity)
    {
        PageTitle::setTitle(trans('plugins/program::activity.edit'));
        return ActivityForm::createFromModel($activity)
            ->setUrl(route('admin.programs.activities.update', [$program->id, $activity->id]))
            ->renderForm();
    }


    public function update(
        Request $request,
        Program $program,
        Activity $activity,
        BaseHttpResponse $response
    ) {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|date',
            'end_time' => 'sometimes|date|after:start_time'
        ]);

        $activity->update($validated);

        return $response
            ->setPreviousUrl(route('admin.programs.activities.index', $program->id, $activity->id))
            ->setNextUrl(route('admin.programs.activities.edit', [$program->id, $activity->id]))
            ->setMessage('Activity updated successfully');

    }


    public function destroy(Program $program, Activity $activity, BaseHttpResponse $response)
    {
        $activity->delete();

        return $response->setMessage('Activity deleted successfully');
    }
}
