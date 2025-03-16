<?php
// app/Plugins/Applicant/Http/Controllers/ApplicantController.php
namespace Botble\Applicant\Http\Controllers;

use Botble\Base\Facades\PageTitle;
use Botble\Applicant\Forms\ApplicantForm;
use Botble\Base\Http\Controllers\BaseController;

use Botble\Applicant\Models\Applicant;
use Botble\Program\Http\Requests\ActivityRequest;
use Botble\Program\Models\Program;
use Botble\Applicant\Tables\ApplicantTable;
use Illuminate\Http\Request;
use Botble\Program\Models\Activity;
use Illuminate\Http\JsonResponse;
class ApplicantController extends BaseController
{
    public function index(ApplicantTable $table)
    {
        return $table->renderTable();
    }

    public function create(Applicant $applicant)
    {
        PageTitle::setTitle(trans('plugins/applicant::applicant.create'));
        return ApplicantForm::create()
            ->setUrl(route('applicant.register.store', $applicant->id))
            ->renderForm();
    }
    public function store(Request $request)
    {
        // Get the authenticated user
        $user = auth()->user();

        // Find or create an applicant by user_id
        $applicant = Applicant::firstOrNew(['user_id' => $user->id]);

        $request->validate([
            'activity_id' => 'required',
            // 'email' => 'required|email|unique:applicants,email',
        ]);

        // Fill the applicant with request data and save
        $applicant->fill($request->only([
            'program_id', 'activity_id', 'user_id', 'highest_degree', 'course_studied', 'passport',
            'motivation_letter', 'other_uploads', 'additional_info', 'resume'
        ]));
        $applicant->save();

        // Update related user information
        $user->update([
            // 'first_name' => $request->input('first_name'),
            // 'last_name' => $request->input('last_name'),
            'phone' => $request->input('phone'),
            'country' => $request->input('country'),
            'occupation' => $request->input('occupation'),
            'birth_date' => $request->input('birth_date'),
            'website' => $request->input('website')
        ]);

        return redirect()->back()->with('success', 'Application successful');
    }

    public function edit(Applicant $applicant)
    {
        //$programs = Program::all();
        //return view('plugins/applicant::admin.edit', compact('applicant', 'programs'));

        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $applicant->name]));

        return ApplicantForm::createFromModel($applicant)->renderForm();
    }

    public function update(Applicant $applicant, ActivityRequest $request)
    {
        ApplicantForm::createFromModel($applicant)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('applicant.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function getActivities(Request $request): JsonResponse
    {
        $programId = $request->input('program_id');

        $activities = Activity::query()
            ->where('program_id', $programId)
            ->wherePublished()
            ->pluck('title', 'id');  // Make sure this is title vs name

        return response()->json([
            'data' => $activities
        ]);
    }


    public function assignProgram(Request $request)
    {
        $request->validate([
            'applicant_id' => 'required|exists:applicants,id',
            'program_id' => 'required|exists:programs,id'
        ]);
        print_r($request);

        $applicant = Applicant::findOrFail($request->applicant_id);
        $program = Program::findOrFail($request->program_id);

        // Use syncWithoutDetaching to avoid duplicates
        $applicant->programs()->syncWithoutDetaching([$program->id]);

        return redirect()->back()
            ->with('success', "Program {$program->name} assigned successfully!");
    }
}
