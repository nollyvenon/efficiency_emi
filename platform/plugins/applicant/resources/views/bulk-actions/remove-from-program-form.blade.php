<div class="mb-3">
    <label class="form-label">{{ trans('plugins/applicant::applicant.select_program_remove') }}</label>
    <select name="program_id" class="form-select" required>
        @foreach($programs as $id => $name)
            <option value="{{ $id }}">{{ $name }}</option>
        @endforeach
    </select>
</div>
