@foreach($activities as $activity)
    <div class="activity-item card mb-2" data-id="{{ $activity->id }}">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h5>{{ $activity->title }}</h5>
                    <p class="text-muted">{{ $activity->start_time->format('M j, Y H:i') }} - {{ $activity->end_time->format('H:i') }}</p>
                </div>
                <div class="col-md-6">
                    {!! Str::limit($activity->description, 150) !!}
                </div>
                <div class="col-md-2 text-end">
                    <button class="btn btn-edit-activity"
                            data-url="{{ route('admin.programs.activities.edit', [$program->id, $activity->id]) }}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-delete-activity"
                            data-url="{{ route('admin.programs.activities.destroy', [$program->id, $activity->id]) }}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
