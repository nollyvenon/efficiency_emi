<section class="shortcode-team shortcode-team-style-4 team__area-four" @style($variablesStyle)>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                {!! Theme::partial('shortcodes.team.partials.header', ['shortcode' => $shortcode, 'wrapperClass' => 'text-center']) !!}
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach($teams as  $team)
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-8">
                    <div class="team__item-four shine-animate-item">
                        <div class="team__thumb-four shine-animate">
                            {{ RvMedia::image($team->photo, $team->name) }}
                        </div>
                        <div class="team__content-four">
                            <h2 class="title"><a href="{{ $team->url }}">{{ $team->name }}</a></h2>
                            @if ($title = $team->title)
                                <span>{{ $title }}</span>
                            @endif

                            <div class="team__social-four">
                                {!! Theme::partial('shortcodes.team.partials.socials', compact('team')) !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if (($buttonLabel = $shortcode->button_label) && ($buttonUrl = $shortcode->button_url))
            <div class="text-center">
                <a href="{{ $buttonUrl }}" class="btn" data-aos="fade-up" data-aos-delay="300">{!! BaseHelper::clean($buttonLabel) !!}</a>
            </div>
        @endif
    </div>
</section>
