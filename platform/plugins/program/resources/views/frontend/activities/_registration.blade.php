@auth
    <div class="activity-registration mt-4">
        @if($activity->registrations()->where('user_id', auth()->id())->exists())
            <p>You are registered for this activity!</p>
            <form action="{{ route('public.activities.unregister', $activity->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Cancel Registration</button>
            </form>
        @else
            <form action="{{ route('public.activities.register', $activity->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label>Additional Notes</label>
                    <textarea name="notes" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Register Now</button>
            </form>
        @endif
    </div>
    <div class="row">
        <div class="col-md-6">
            {!! Form::text('location_address', null, ['label' => 'Address', 'class' => 'form-control map-location']) !!}
            {!! Form::hidden('latitude', null, ['class' => 'map-lat']) !!}
            {!! Form::hidden('longitude', null, ['class' => 'map-lng']) !!}
        </div>
        <div class="col-md-6">
            <div id="map-preview" style="height: 200px"></div>
        </div>
    </div>
@else
    <div class="alert alert-info">
        Please <a href="{{ route('login') }}">login</a> to register for this activity.
    </div>
@endauth

@if($activity->latitude && $activity->longitude)
    <div class="activity-map mt-4">
        <div id="map-{{ $activity->id }}" style="height: 300px"></div>
        <script>
            function initMap{{ $activity->id }}() {
                const map = new google.maps.Map(document.getElementById('map-{{ $activity->id }}'), {
                    center: { lat: {{ $activity->latitude }}, lng: {{ $activity->longitude }} },
                    zoom: 15
                });
                new google.maps.Marker({
                    position: { lat: {{ $activity->latitude }}, lng: {{ $activity->longitude }} },
                    map: map,
                    title: "{{ $activity->title }}"
                });
            }
        </script>
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap{{ $activity->id }}">
        </script>
    </div>
@endif
