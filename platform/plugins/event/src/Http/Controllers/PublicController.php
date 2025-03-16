<?php

namespace Botble\Event\Http\Controllers;

use Botble\Event\Models\Event;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Routing\Controller;

class PublicController extends Controller
{
    public function events()
    {
        SeoHelper::setTitle(__('Events'));

        Theme::breadcrumb()->add(__('Events'), route('public.events'));

        $events = Event::query()
            ->wherePublished()
            ->orderByDesc('created_at')
            ->paginate(10);

        return Theme::scope('event.events', compact('events'), 'plugins/event::themes.event')->render();
    }
}
