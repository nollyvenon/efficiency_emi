<?php

namespace Botble\Event\Forms;

use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Event\Http\Requests\EventRegistrationRequest;
use Botble\Event\Models\EventRegistration;

class EventRegistrationForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(EventRegistration::class)
            ->setValidatorClass(EventRegistrationRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required()->colspan(2))

            ->add('description', EditorField::class, DescriptionFieldOption::make()->colspan(2))
            ->add('content', EditorField::class, ContentFieldOption::make()->colspan(2))
            ->add(
                'slug',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/program::activity.forms.slug'))
                    ->placeholder(trans('plugins/program::activity.forms.slug_placeholder'))
                    ->maxLength(120)
            )
            ->add('location_address', TextareaField::class, TextareaFieldOption::make()
                ->label(trans('plugins/program::activity.forms.location_address'))
                ->colspan(2))
            ->add(
                'start_time',
                DatePickerField::class,
                DatePickerFieldOption::make()
                    ->label(trans('plugins/program::activity.forms.start_date'))
                    ->defaultValue(null)
                    ->colspan(2)
            )
            ->add(
                'end_time',
                DatePickerField::class,
                DatePickerFieldOption::make()
                    ->label(trans('plugins/program::activity.forms.end_date'))
                    ->defaultValue(null)
                    ->colspan(2)
            )
            ->add('photo', MediaImageField::class, MediaImageFieldOption::make()->label(trans('plugins/program::program.forms.photo')))

            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('start_time');
    }
}
