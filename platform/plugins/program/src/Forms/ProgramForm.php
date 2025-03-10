<?php

namespace Botble\Program\Forms;

use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Program\Http\Requests\ProgramRequest;
use Botble\Program\Models\Program;
use Illuminate\Support\Arr;

class ProgramForm extends FormAbstract
{
    public function setup(): void
    {

        $this
            ->model(Program::class)
            ->setValidatorClass(ProgramRequest::class)
            ->columns()
            ->add('name', TextField::class, NameFieldOption::make()->required()->colspan(2))
            ->add('description', EditorField::class, ContentFieldOption::make()->colspan(2));

        $this
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->add('photo', MediaImageField::class, MediaImageFieldOption::make()->label(trans('plugins/program::program.forms.photo')))
            ->setBreakFieldPoint('status');
    }
}
