@extends('core/base::layouts.master')

@section('content')
    <div class="container">
        <h1 class="mb-4">Our Programs</h1>

        <div class="row">
            @foreach($programs as $program)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $program->name }}</h5>
                            <p class="card-text">{{ Str::limit($program->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('public.programs.show', $program->slug) }}" class="btn btn-primary">
                                    View Details
                                </a>
                                <small class="text-muted">
                                    {{ $program->activities_count }} Activities
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $programs->links() }}
    </div>
@stop
