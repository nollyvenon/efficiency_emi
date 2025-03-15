<div class="mb-3">
    <label class="form-label">{{ trans('plugins/applicant::applicant.current_program') }}</label>
    <select name="from_program_id" class="form-select" required>
        @foreach($programs as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">{{ trans('plugins/applicant::applicant.target_program') }}</label>
    <select name="to_program_id" class="form-select" required>
        @foreach($programs as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
    </select>
</div>
