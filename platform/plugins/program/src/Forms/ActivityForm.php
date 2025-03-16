<?php

namespace Botble\Program\Forms;

use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;

use Botble\Program\Http\Requests\ActivityRequest;
use Botble\Program\Http\Requests\ProgramRequest;
use Botble\Program\Models\Activity;
use Botble\Program\Models\Program;
use Illuminate\Support\Arr;

class ActivityForm extends FormAbstract
{
    public function setup(): void
    {
        //$socials = $this->model->socials ?? [];

        $this
            ->model(Activity::class)
            ->setValidatorClass(ActivityRequest::class)
            ->columns()
            ->add('title', TextField::class, NameFieldOption::make()->required()->colspan(2))

            ->add('description', EditorField::class, DescriptionFieldOption::make()->colspan(2))
            ->add('content', EditorField::class, ContentFieldOption::make()->allowedShortcodes()->colspan(2))
            ->add(
                'slug',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/program::activity.forms.slug'))
                    ->placeholder(trans('plugins/program::activity.forms.slug_placeholder'))
                    ->maxLength(120)
            )
            //->add('photo', MediaImageField::class, MediaImageFieldOption::make()->label(trans('plugins/program::program.forms.photo')))
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
            );

        $this
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->add('photo', MediaImageField::class, MediaImageFieldOption::make()->label(trans('plugins/program::activity.forms.photo')))

            ->setBreakFieldPoint('start_time');
    }
}
