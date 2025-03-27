@php
    $serviceSidebar = dynamic_sidebar('service_sidebar');
    Theme::set('pageTitle', __('Program Details'))
@endphp

<section class="services__details-area">
    <div class="container">
        <div class="services__details-wrap">
            <div class="row">
                <div class="{{ $serviceSidebar ? 'col-70' : 'col-100' }} order-0 order-lg-2">

                    <div class="services__details-content">

                        <h2 class="title">{{ $program->name }}</h2>

                        @if($image = $program->photo)
                            <div class="services__details-thumb">
                                {{ RvMedia::image($image, $program->name, 'medium-rectangle') }}
                            </div>
                        @endif

                        @if ($author = $program->author)
                            <div class="blog-avatar">
                                <div class="avatar-thumb">
                                    {{ RvMedia::image($author->avatar_url, 'thumb') }}
                                </div>
                                <div class="avatar-content">
                                    {!! __('By :author', [
                                        'author' => sprintf('<strong>%s</strong>', $author->name, $author->name)
                                    ]) !!}
                                </div>
                            </div>
                        @endif

                        @if ($description = $program->description)
                            <p>{!! BaseHelper::clean($description) !!}</p>
                        @endif

                        @if ($content = $program->content)
                            <p>{!! BaseHelper::clean($content) !!}</p>
                        @endif

                        <div class="ck-content">
                            {!! BaseHelper::clean($program->content) !!}
                        </div>

                        @if ($start_time = $program->start_time)
                            <p>Start date: {!! Theme::formatDate($start_time) !!}</p>
                        @endif

                        @if ($end_time = $program->end_time)
                            <p>End date: {!! Theme::formatDate($end_time) !!}</p>
                        @endif

                        @if ($location_address = $program->location_address)
                            <p>{!! BaseHelper::clean($location_address) !!}</p>
                        @endif

                        @if ($capacity = $program->capacity)
                            <p>Capacity: {!! BaseHelper::clean($capacity) !!}</p>
                        @endif

                        @if ($latitude = $program->latitude)
                            <p>Latitude: {!! BaseHelper::clean($latitude) !!}</p>
                        @endif

                        @if ($longitude = $program->longitude)
                            <p>Longitude: {!! BaseHelper::clean($longitude) !!}</p>
                        @endif

                       {{-- {!! $applyRegisterForm->renderForm() !!} --}}
                    </div>
                </div>

                @if($serviceSidebar)
                    <div class="col-30">
                        <aside class="services__sidebar">
                            {!! $serviceSidebar !!}
                        </aside>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
