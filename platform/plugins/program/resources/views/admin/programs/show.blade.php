@extends('core/base::layouts.master')

@section('content')
    <div class="container">
        <div class="mb-4">
            <h1>{{ $program->name }}</h1>
            <p class="lead">{{ $program->start_date->format('M Y') }} to {{ $program->end_date->format('M Y') }}</p>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="mb-4">
                    {!! $program->description !!}
                </div>

                <h3>Activities</h3>
                <div class="list-group">
                    @foreach($program->activities as $activity)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>{{ $activity->title }}</h5>
                                    <p class="mb-1">{{ $activity->description }}</p>
                                    <small class="text-muted">
                                        {{ $activity->start_time->format('M j, Y H:i') }} -
                                        {{ $activity->end_time->format('H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Program Details</h5>
                        <dl>
                            <dt>Duration:</dt>
                            <dd>{{ $program->start_date->diffInDays($program->end_date) }} days</dd>

                            <dt>Status:</dt>
                            <dd>{{ $program->is_active ? 'Active' : 'Completed' }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
