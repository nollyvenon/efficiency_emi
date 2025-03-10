<!-- resources/views/admin/programs/edit.blade.php -->
@extends('core/base::forms.form')

@section('form')
    @parent

    <div class="mt-4">
        <h4>Activities</h4>
        <div id="activities-list">
            @foreach($program->activities as $activity)
                @include('plugins/program::activities.item', ['activity' => $activity])
            @endforeach
        </div>

        <button type="button"
                class="btn btn-info"
                data-bs-toggle="modal"
                data-bs-target="#add-activity-modal">
            Add Activity
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="add-activity-modal">
        <!-- Activity form here -->
    </div>
@stop
