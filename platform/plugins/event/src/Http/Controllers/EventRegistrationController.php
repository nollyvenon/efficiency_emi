<?php

namespace Botble\Event\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Event\Models\EventRegistration;
use Botble\Event\Models\Event;
use Illuminate\Http\Request;

class EventRegistrationController extends BaseController
{
    public function index($id)
    {
        $registrations = EventRegistration::where('event_id', $id)->get();
        return view('plugins.event::registrations', compact('registrations'));
    }

    public function store(Request $request, $id)
    {
        EventRegistration::create([
            'event_id' => $id,
            'user_id' => $request->user_id,
            'email' => $request->email,
        ]);
        return redirect()->back()->with('success', 'Successfully registered for the event!');
    }
}
