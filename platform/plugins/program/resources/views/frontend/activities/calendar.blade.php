@extends('core/base::layouts.master')

@section('content')
    <div class="container">
        <div id="calendar" class="mb-4"></div>
    </div>

    @push('footer')
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const calendarEl = document.getElementById('calendar');
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    events: '{{ route('public.activities.calendar') }}',
                    eventClick: function(info) {
                        window.location.href = '/programs/' + info.event.extendedProps.program_slug;
                    }
                });
                calendar.render();
            });
        </script>
    @endpush
@stop
