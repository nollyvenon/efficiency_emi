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
        $user = $this->model->user ?? auth()->user();

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
                'activities_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/applicant::applicant.forms.activities'))
                    ->choices([])
                    ->selected(function () {
                        return $this->model->activities()->pluck('id')->all();
                    })
                    //->multiple()
                    ->searchable()
                    ->addAttribute('data-depends-on', '#program_id')
            )
            ->add(
                'first_name',  // Add separate first/last name fields
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('First Name'))
                    ->value($user->first_name ?? '')
                    ->required()
                    ->disabled()
            )
            ->add(
                'last_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('Last Name'))
                    ->value($user->last_name ?? '')
                    ->required()
                    ->disabled()
            )
            ->add(
                'phone',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/applicant::applicant.forms.phone'))
                    ->placeholder(trans('plugins/applicant::applicant.forms.phone_placeholder'))
                    ->maxLength(120)
                    ->value($this->model->phone ?? '')
                    ->disabled()
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
                    ->disabled()
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
                    ->defaultValue(null)
                    // ->colspan(2)
            )
            ->add(
                'highest_degree',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/applicant::applicant.forms.highest_degree'))
                    ->defaultValue(null)
                    //->colspan(2)
            )
            ->add(
                'course_studied',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/applicant::applicant.forms.course_studied'))
                    ->defaultValue(null)
            //    ->colspan(2)
            )
            ->add(
                'birth_date',
                DatePickerField::class,
                DatePickerFieldOption::make()
                    ->label(trans('plugins/applicant::applicant.forms.birth_date'))
                    ->defaultValue(null)
            //    ->colspan(2)
            )
            ->add(
                'website',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/applicant::applicant.forms.website_placeholder'))
                    ->defaultValue(null)
            //    ->colspan(2)
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
                const activitySelect = $('#activities_id');


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



