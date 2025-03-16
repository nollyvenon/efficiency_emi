<?php

namespace Botble\Applicant\Forms;

use Botble\Applicant\Models\Applicant;
use Botble\Base\Forms\FieldOptions\ContentFieldOption;
use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\InputFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\EditorField;
use Botble\Base\Forms\Fields\MediaFileField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;

use Botble\Program\Models\Program;
use Illuminate\Support\Arr;

class ApplicantForm extends FormAbstract
{
    public function setup(): void
    {
        //$socials = $this->model->socials ?? [];
        $programs = Program::query()->pluck('name', 'id')->all();
        $defaultProgram = Program::query()->first();
        //$user = $this->model->user ?? auth()->user();

        // Get the user data safely
        $user = ($this->model instanceof Applicant && $this->model->exists)
            ? $this->model->user
            : auth()->user();

        // Check if we're editing an existing applicant
        /*$isEditing = ($this->model instanceof Applicant)
            ? $this->model->exists
            : !empty($this->model['id']);*/

        $applicant = Applicant::firstOrNew(['user_id' => $user->id]);

        $this
            ->model(Applicant::class)
            //->setValidatorClass(ApplicantRequest::class)
            ->columns()
            ->add(
                'program_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/applicant::applicant.forms.programs'))
                    ->choices(['' => trans('plugins/applicant::applicant.select_program')] + $programs)
                    ->selected($defaultProgram ? $defaultProgram->id : null)
                    ->searchable()
                    ->addAttribute('data-activity-source', route('applicants.get-activities'))
            )
            ->add(
                'activity_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/applicant::applicant.forms.activities'))
                    ->choices([])
                    //->selected(function () {
                    //    return $this->model->activities()->pluck('id')->all();
                   //})
                    ->selected(old('activity_id', $this->model->activity_id ?? ''))
                    ->searchable()
                    ->addAttribute('data-depends-on', '#program_id')
            )
            ->add(
                'first_name',  // Add separate first/last name fields
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('First Name'))
                    ->value(old('first_name', $user->first_name ?? ''))
                    ->required()
                    ->addAttribute('disabled', !empty($user->first_name))
            )
            ->add(
                'last_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('Last Name'))
                    ->value($user->last_name ?? '')
                    ->required()
                    ->addAttribute('disabled', !empty($user->last_name))
            )
            ->add(
                'phone',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/applicant::applicant.forms.phone'))
                    ->placeholder(trans('plugins/applicant::applicant.forms.phone_placeholder'))
                    ->maxLength(120)
                    ->value($this->model->phone ?? '')
                    ->addAttribute('disabled', !empty($user->phone))
            )
            ->add(
                'email',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/applicant::applicant.forms.email'))
                    ->placeholder(trans('plugins/applicant::applicant.forms.email_placeholder'))
                    ->maxLength(120)
                    ->value($user->email ?? '')
                    ->required()
                    ->addAttribute('disabled', !empty($user->email))
            )
            ->add(
                'country',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/applicant::applicant.forms.country'))
                    ->defaultValue(null)
                    ->value($user->country ?? '')
                    ->required()
            )
            ->add(
                'occupation',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/applicant::applicant.forms.occupation'))
                    //->defaultValue(null)
                    ->value($user->occupation ?? '')
                    // ->colspan(2)
            )
            ->add(
                'highest_degree',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/applicant::applicant.forms.highest_degree'))
                    ->value(old('highest_degree', $this->model->highest_degree ?? ''))
                    //->colspan(2)
            )
            ->add(
                'course_studied',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/applicant::applicant.forms.course_studied'))
                    ->value(old('course_studied', $this->model->course_studied ?? ''))
            //    ->colspan(2)
            )
            ->add(
                'birth_date',
                DatePickerField::class,
                DatePickerFieldOption::make()
                    ->label(trans('plugins/applicant::applicant.forms.birth_date'))
                    ->value(old('birth_date', $user->birth_date ?? ''))
            )
            ->add(
                'website',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/applicant::applicant.forms.website_placeholder'))
                    ->value(old('website', $user->website ?? ''))
            )
            ->add(
                'passport',
                MediaFileField::class,
                InputFieldOption::make()
                    ->label(__('Passport :number', ['number' => 1]))
            )
            ->add(
                'resume',
                MediaFileField::class,
                InputFieldOption::make()
                    ->label(__('Resume :number', ['number' => 2]))
            )
            ->add(
                'motivation_letter',
                MediaFileField::class,
                InputFieldOption::make()
                    ->label(__('Motivation Letter :number', ['number' => 3]))
            )
            ->add(
                'other_uploads',
                MediaFileField::class,
                InputFieldOption::make()
                    ->label(__('Other Uploads :number', ['number' => 4]))
            )
            ->add(
                'user_id',
                TextField::class,
                TextFieldOption::make()
                    ->label(false)
                    ->value($user->id ?? '')
                    ->required()
                    //->addAttribute('style', 'display: none;')
                    ->addAttribute('class', 'sr-only')
            );

        $this
            ->add('status', SelectField::class, StatusFieldOption::make())

            ->setBreakFieldPoint('status');
        $this->addAjaxScript();
    }

    // In your ApplicantForm class
    protected function addAjaxScript(): void
    {
        $this->add('after_form', 'html', [
            'html' => <<<HTML
        <script>
            $(document).ready(function() {
                const programSelect = $('#program_id');
                const activitySelect = $('#activity_id');


                // Clear and disable activities on init
                activitySelect.html('<option value="">Select program first</option>');

                programSelect.on('change', function() {
                    const programId = $(this).val();
                    const url = programSelect.data('activity-source');


                    if (programId) {
                        $.ajax({
                            url: url,
                            data: { program_id: programId },
                            success: function(response) {
                                // Clear existing options
                                activitySelect.html('');

                                // Add default option
                                activitySelect.append(
                                    $('<option></option>').val('').text('Select Activity')
                                );

                                // Add new options
                                if (response.data && Object.keys(response.data).length > 0) {
                                    $.each(response.data, function(id, name) {
                                        activitySelect.append(
                                            $('<option></option>').val(id).text(name)
                                        );
                                    });

                                } else {
                                    activitySelect.append(
                                        $('<option></option>').val('').text('No activities available')
                                    );
                                }

                                // Refresh Select2
                                activitySelect.trigger('change.select2');
                            },
                            error: function(xhr) {
                                console.error('Error:', xhr.responseText);
                                activitySelect.html(
                                    '<option value="">Error loading activities</option>'
                                ).trigger('change.select2');
                            }
                        });
                    } else {
                        activitySelect.html('<option value="">Select program first</option>')
                            .trigger('change.select2');
                    }
                });

                // Trigger initial load if program is preselected
                if (programId.val()) {
                    programSelect.trigger('change');
                }
            });
        </script>
        HTML
        ]);
    }
}



