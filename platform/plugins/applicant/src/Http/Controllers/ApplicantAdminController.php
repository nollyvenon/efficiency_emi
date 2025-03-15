<?php
namespace Botble\Applicant\Http\Controllers;

use Botble\Applicant\Models\Applicant;

use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class ApplicantAdminController extends BaseController
{
    public function index()
    {
        $applicants = Applicant::where('status', 'pending')->get();
        return view('applicant::admin.applicants', compact('applicants'));
    }

    public function updateStatus(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id);
        $applicant->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status updated');
    }
}
