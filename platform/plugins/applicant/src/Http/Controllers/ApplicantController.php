<?php
namespace Botble\Applicant\Http\Controllers;

use Botble\Base\Facades\PageTitle;
use Botble\Applicant\Forms\ApplicantForm;
use Botble\Base\Http\Controllers\BaseController;

use Botble\Applicant\Models\Applicant;
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
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:applicants,email',
        ]);

        Applicant::create($request->all());

        return redirect()->back()->with('success', 'Registration successful');
    }

    public function edit(Applicant $applicant)
    {
        $programs = Program::all();

        return view('plugins/applicant::admin.edit', compact('applicant', 'programs'));
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
}
