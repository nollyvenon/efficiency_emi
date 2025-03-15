@php use Botble\Program\Forms\ActivityForm; @endphp
<div class="modal fade" id="add-activity-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.programs.activities.store', $program->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    @php
                        echo ActivityForm::create()->renderForm();
                    @endphp
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('footer')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete Activity
            document.querySelectorAll('.btn-delete-activity').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('Delete this activity?')) {
                        fetch(this.dataset.url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                        }).then(response => {
                            if (response.ok) window.location.reload();
                        });
                    }
                });
            });

            // Edit Activity Modal
            document.querySelectorAll('.btn-edit-activity').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    fetch(this.dataset.url)
                        .then(response => response.text())
                        .then(html => {
                            const modal = new bootstrap.Modal(document.getElementById('edit-activity-modal'));
                            document.getElementById('edit-activity-modal-content').innerHTML = html;
                            modal.show();
                        });
                });
            });
        });
    </script>
@endpush

<!-- Edit Activity Modal -->
<div class="modal fade" id="edit-activity-modal">
    <div class="modal-dialog">
        <div class="modal-content" id="edit-activity-modal-content"></div>
    </div>
</div>
