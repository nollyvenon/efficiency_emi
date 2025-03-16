<?php

namespace Botble\Event\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Event\Forms\EventRegistrationForm;
use Botble\Event\Http\Requests\EventRequest;
use Botble\Event\Models\Event;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Event\Models\EventRegistration;
use Botble\Event\Tables\EventTable;
use Botble\Event\Forms\EventForm;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Theme\Facades\Theme;

class EventController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans(trans('plugins/event::event.name')), route('event.index'));
    }

    public function index(EventTable $table)
    {
        $this->pageTitle(trans('plugins/event::event.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/event::event.create'));

        return EventForm::create()->renderForm();
    }

    public function store(EventRequest $request)
    {

        $form = EventForm::create()->setRequest($request);
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('event.index'))
            ->setNextUrl(route('event.edit', $form->getModel()->getKey()))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(Event $event)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $event->name]));

        return EventForm::createFromModel($event)->renderForm();
    }

    public function update(Event $event, EventRequest $request)
    {
        EventForm::createFromModel($event)
            ->setRequest($request)
            ->save();

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('event.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function show(int|string $id)
    {
        $event = Event::query()
            ->where([
                'slug' => $id,
               // 'user_id' => auth('customer')->id(),
            ])
            //->with(['address', 'products'])
            ->firstOrFail();

        $eventRegisterForm = EventRegistrationForm::createFromModel($event);

        SeoHelper::setTitle(__('Efficiency EMI Event: :id', ['id' => $event->name]));

        Theme::breadcrumb()
            ->add(
                __('Event detail: :id', ['id' => $event->name]),
                route('public.events.show', $id)
            );

        return Theme::scope(
            'event.event',
            compact('event', 'eventRegisterForm'),
            'plugins/event::event.name'
        )->render();
    }

    public function destroy(Event $event)
    {
        return DeleteResourceAction::make($event);
    }
}
