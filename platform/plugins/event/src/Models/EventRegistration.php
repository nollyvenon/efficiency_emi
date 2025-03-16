<?php

namespace Botble\Event\Models;

use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Enums\OrderCancellationReasonEnum;
use Botble\Event\Http\Requests\EventRequest;
use Botble\Event\Models\Event;

class EventRegistration extends BaseModel
{
    protected $table = 'event_registrations';
    protected $fillable = ['event_id', 'user_id', 'email'];

    public function setup(): void
    {
        $this
            ->contentOnly()
            ->setFormOption('id', 'cancel-order-form')
            ->setValidatorClass(EventRequest::class)
            ->setUrl(route('event.register.store', $this->getModel()->id))
            ->add(
                'cancellation_reason',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Choose a Reason for Order Cancellation'))
                    ->choices([
                        '' => __('Choose a reason...'),
                        ...OrderCancellationReasonEnum::labels(),
                    ])
                    ->required()
            )
            ->add(
                'cancellation_reason_description',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(__('Description'))
            );
    }
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
