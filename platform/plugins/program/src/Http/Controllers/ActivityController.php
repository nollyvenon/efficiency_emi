<?php

namespace Botble\Program\Http\Controllers;

use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Program\Models\Activity;
use Botble\Program\Models\Program;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Program\Tables\ActivityTable;
use Illuminate\Http\Request;

class ActivityController extends BaseController
{
    public function index(Program $program, ActivityTable $table)
    {
        PageTitle::setTitle("Activities for {$program->name}");

        return $table
            ->setProgramId($program->id) // Must be called first
            ->renderTable();
    }

    public function create(Program $program)
    {
        return view('plugins/program::activities.create', compact('program'));
    }

    public function store(Request $request, Program $program, BaseHttpResponse $response)
    {
        // Add validation rules
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time'
        ]);

        $activity = $program->activities()->create($validated);

        return $response
            ->setPreviousUrl(route('admin.programs.activities.index', $program->id))
            ->setNextUrl(route('admin.programs.activities.edit', [$program->id, $activity->id]));
    }

    public function edit(Program $program, Activity $activity)
    {
        return view('plugins/program::admin.activities.edit', compact('program', 'activity'));
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

        return $response->setPreviousUrl(route('admin.programs.activities.index', $program->id));
    }

    public function destroy(Program $program, Activity $activity, BaseHttpResponse $response)
    {
        $activity->delete();

        return $response->setMessage('Activity deleted successfully');
    }
}
