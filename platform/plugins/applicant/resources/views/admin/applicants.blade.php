@foreach ($applicants as $applicant)
    <p>{{ $applicant->name }} - {{ $applicant->email }}
        <a href="{{ route('admin.applicants.updateStatus', ['id' => $applicant->id, 'status' => 'selected']) }}">Select</a>
    </p>
@endforeach


<?php
$this->add('after_form', 'html', [
    'html' => <<<HTML
    <script>
        $(document).ready(function() {
            // Dynamic activity loading
            const programSelect = $('#program_id');
            const activitySelect = $('#activities_id');

            programSelect.on('change', function() {
                const programId = $(this).val();
                const url = activitySelect.data('url').replace(':programId', programId);

                if (programId) {
                    $.ajax({
                        url: url,
                        method: 'GET',
                        success: function(response) {
                            activitySelect.html('');
                            if (response.data && Object.keys(response.data).length) {
                                $.each(response.data, function(key, value) {
                                    activitySelect.append(
                                        $('<option></option>').attr('value', key).text(value)
                                    );
                                });
                            } else {
                                activitySelect.append(
                                    $('<option></option>').text('No activities available')
                                );
                            }
                            activitySelect.trigger('change.select2');
                        }
                    });
                }
            });

            // Trigger initial load if program is preselected
            if (programSelect.val()) {
                programSelect.trigger('change');
            }
        });
    </script>
    HTML
]);
