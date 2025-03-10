<?php

namespace Botble\Program\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Program\Models\Program;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;

class PublicController extends BaseController
{

    public function index(Request $request)
    {
        $query = Program::query()
            ->where('status', true)
            ->withCount('activities');

        // Search filter
        if ($request->has('search')) {
            $query->where('name', 'LIKE', '%'.$request->input('search').'%');
        }

        // Date filter
        if ($request->has('date')) {
            $query->whereDate('start_date', '<=', $request->input('date'))
                ->whereDate('end_date', '>=', $request->input('date'));
        }
        SeoHelper::setTitle(__('Programs'));

        $programs = $query->withCount('activities')->paginate(10);

        return Theme::scope('plugins/program::frontend.programs.index', compact('programs'))->render();
    }

    public function show(string $slug)
    {
        $program = Program::query()
            ->where('slug', $slug)
            ->where('status', true)
            ->with(['activities' => function ($query) {
                $query->orderBy('start_time');
            }])
            ->firstOrFail();

        SeoHelper::setTitle($program->name);

        return Theme::scope('plugins/program::show', compact('program'))->render();
    }

    public function ical(Activity $activity)
    {
        $icalContent = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//Your Organization//NONSGML Calendar//EN
BEGIN:VEVENT
UID:" . md5($activity->id) . "@yourdomain.com
DTSTAMP:" . now()->format('Ymd\THis\Z') . "
DTSTART:" . $activity->start_time->format('Ymd\THis\Z') . "
DTEND:" . $activity->end_time->format('Ymd\THis\Z') . "
SUMMARY:" . $activity->title . "
DESCRIPTION:" . strip_tags($activity->description) . "
LOCATION:" . ($activity->location ?? 'Online') . "
END:VEVENT
END:VCALENDAR";

        return response($icalContent)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="activity.ics"');
    }
}
