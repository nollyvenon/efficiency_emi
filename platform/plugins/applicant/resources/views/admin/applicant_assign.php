<!-- resources/views/plugins/applicant/index.blade.php -->
<form action="{{ route('applicants.assign-program') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-md-4">
            <select name="applicant_id" class="form-control" required>
                <option value="">Select Applicant</option>
                @foreach($applicants as $applicant)
                <option value="{{ $applicant->id }}">
                    {{ $applicant->user->name }} ({{ $applicant->user->email }})
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <select name="program_id" class="form-control" required>
                <option value="">Select Program</option>
                @foreach($programs as $program)
                <option value="{{ $program->id }}">{{ $program->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-link"></i> Assign Program
            </button>
        </div>
    </div>
</form>
