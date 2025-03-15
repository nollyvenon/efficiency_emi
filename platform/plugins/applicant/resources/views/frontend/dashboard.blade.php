@extends('layouts.master')

@section('content')
    <div class="applicant-dashboard">
        <h2>Your Programs</h2>
        <div class="program-list">
            @foreach(auth()->user()->applicant->programs as $program)
                <div class="program-card">
                    <h3>{{ $program->name }}</h3>
                    <p>{{ $program->description }}</p>
                    <a href="{{ route('programs.show', $program) }}">View Program Details</a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
