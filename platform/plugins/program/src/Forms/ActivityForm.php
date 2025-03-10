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
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\Fields\TreeCategoryField;
use Botble\Base\Forms\FormAbstract;
use Botble\Blog\Models\Category;
use Botble\Blog\Models\Post;
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
            ->setValidatorClass(ProgramRequest::class)
            ->columns()
            ->add('name', TextField::class, NameFieldOption::make()->required()->colspan(2))
            ->add(
                'program_id[]',
                TreeCategoryField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/program::activity.form.program'))
                    ->choices(function () {
                        return Program::query()
                            ->wherePublished()
                            ->select(['id', 'name'])
                            ->get();
                    })
                    ->when($this->getModel()->getKey(), function (SelectFieldOption $fieldOption) {
                        /**
                         * @var Activity $activity
                         */
                        $activity = $this->getModel();

                        return $fieldOption->selected($activity->programs()->pluck('program_id')->all());
                    }, function (SelectFieldOption $fieldOption) {
                        return $fieldOption
                            ->selected(
                                Category::query()
                                    ->wherePublished()
                                    ->where('is_default', 1)
                                    ->pluck('id')
                                    ->all()
                            );
                    })
            )
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
            ->add(
                'start_date',
                DatePickerField::class,
                DatePickerFieldOption::make()
                    ->label(trans('plugins/program::activity.forms.start_date'))
                    ->defaultValue(null)
                    ->colspan(1)
            )
            ->add(
                'end_date',
                DatePickerField::class,
                DatePickerFieldOption::make()
                    ->label(trans('plugins/program::activity.forms.end_date'))
                    ->defaultValue(null)
                    ->colspan(1)
            );

        $this
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->add('photo', MediaImageField::class, MediaImageFieldOption::make()->label(trans('plugins/program::activity.forms.photo')))

            ->setBreakFieldPoint('description');
    }
}
