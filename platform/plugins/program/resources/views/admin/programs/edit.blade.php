@extends('core/base::forms.form')

@section('form')
    @parent

    <div class="mt-4">
        <h4>Activities</h4>
        <div id="activities-container">
            @include('plugins/program::admin.activities.list', ['activities' => $program->activities])
        </div>

        <button type="button"
                class="btn btn-add-activity"
                data-program="{{ $program->id }}"
                data-url="{{ route('admin.programs.activities.create', $program->id) }}">
            Add Activity
        </button>
    </div>

    @include('plugins/program::admin.activities.modal')
@stop
