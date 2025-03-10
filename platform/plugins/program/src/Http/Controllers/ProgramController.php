<?php

namespace Botble\Program\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Program\Tables\ProgramTable;
use Botble\Program\Forms\ProgramForm;
use Botble\Program\Models\Program;
use Botble\Program\Http\Requests\ProgramRequest;
use Exception;
use Illuminate\Http\Request;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Theme\Facades\Theme;

class ProgramController extends BaseController
{

    /*public function index()
    {
        $programs = Program::query()
            //->where('status', true)
            ->withCount('activities')
            ->paginate(10);

        SeoHelper::setTitle(__('Programs'));

        return Theme::scope('plugins/program::index', compact('programs'))->render();
    }*/

    public function index(ProgramTable $table)
    {
        return $table->renderTable();
    }

    public function create()
    {
        PageTitle::setTitle(trans('plugins/program::program.create'));
        return ProgramForm::create()->renderForm();
    }

    public function store(ProgramRequest $request)
    {
        $program = Program::query()->create($request->validated());

        event(new CreatedContentEvent(PROGRAM_MODULE_SCREEN_NAME, $request, $program));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('program.index'))
            ->setNextUrl(route('program.edit', $program->getKey()))
            ->withCreatedSuccessMessage();
    }

    public function edit(Program $program, Request $request)
    {
        event(new BeforeEditContentEvent($request, $program));

        PageTitle::setTitle(trans('core/base::forms.edit_item', ['name' => $program->name]));

        return ProgramForm::createFromModel($program)->renderForm();

    }

    public function update(Program $program, ProgramRequest $request)
    {
        $program->update($request->validated());

        event(new UpdatedContentEvent(PROGRAM_MODULE_SCREEN_NAME, $request, $program));

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('program.index'))
            ->withUpdatedSuccessMessage();

    }

    public function destroy(Program $program, Request $request)
    {
        try {
            $program->delete();

            event(new DeletedContentEvent(PROGRAM_MODULE_SCREEN_NAME, $request, $program));

            return $this
                ->httpResponse()
                ->withDeletedSuccessMessage();
        } catch (Exception $exception) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

}
