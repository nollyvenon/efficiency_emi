<div class="program-management">
    <h4>Current Programs</h4>
    @foreach($applicant->programs as $program)
        <div class="program-item">
            {{ $program->name }}
            <a href="{{ route('applicants.transfer', [$applicant, $program]) }}">Transfer</a>
            <form method="POST" action="{{ route('applicants.remove-program', [$applicant, $program]) }}">
                @csrf @method('DELETE')
                <button type="submit">Remove</button>
            </form>
        </div>
    @endforeach

    <h4>Assign to New Program</h4>
    <form method="POST" action="{{ route('applicants.assign-program', $applicant) }}">
        @csrf
        <select name="program_id">
            @foreach($programs as $program)
                <option value="{{ $program->id }}">{{ $program->name }}</option>
            @endforeach
        </select>
        <button type="submit">Assign</button>
    </form>
</div>
