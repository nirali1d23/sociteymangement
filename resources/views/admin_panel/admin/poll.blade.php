@extends('admin_panel.layouts.app')

@section('content')

<div class="pagetitle">
    <h1>Poll</h1>
</div>

<div class="card">
    <div class="card-body">
        <br>

        <a class="btn btn-primary float-end"
           href="javascript:void(0)"
           id="createNewPoll">
            Create New
        </a>

        <h5 class="card-title">Poll List</h5>

        <table class="table table-bordered border-primary data-table">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Options</th>
                    <th>Votes</th>
                    <th width="150px">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- ================= MODAL ================= -->
    <div class="modal fade" id="ajaxModel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Create New Poll</h4>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body">

                    <button type="button"
                            class="btn btn-success float-end mb-2"
                            id="addOption">
                        Add Option
                    </button>

                    <form id="pollForm">

                        <!-- Question -->
                        <div class="mb-3">
                            <label class="form-label">Question</label>
                            <input type="text"
                                   class="form-control"
                                   name="question"
                                   placeholder="Enter Question"
                                   required>
                        </div>

                        <!-- Options -->
                        <div id="options">

                            <div class="mb-3 option-row">
                                <label class="form-label">Option 1</label>
                                <input type="text"
                                       class="form-control"
                                       name="option[]"
                                       placeholder="Enter Option 1"
                                       required>
                            </div>

                            <div class="mb-3 option-row">
                                <label class="form-label">Option 2</label>
                                <input type="text"
                                       class="form-control"
                                       name="option[]"
                                       placeholder="Enter Option 2"
                                       required>
                            </div>

                        </div>

                        <!-- Buttons -->
                        <div class="mt-3">
                            <button class="btn btn-primary" id="savePoll">
                                Save Poll
                            </button>
                            <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">
                                Close
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ================= INLINE SCRIPT ================= --}}
<script type="text/javascript">
$(function () {

    // CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // DATATABLE
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('poll.data') }}",
        columns: [
            { data: 'question', name: 'question' },
            { data: 'options_count', name: 'options_count' },
            { data: 'votes_count', name: 'votes_count' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    let optionCount = 2;

    // OPEN MODAL
    $('#createNewPoll').click(function () {
        optionCount = 2;
        $('#pollForm')[0].reset();

        $('#options').html(`
            <div class="mb-3 option-row">
                <label class="form-label">Option 1</label>
                <input type="text"
                       class="form-control"
                       name="option[]"
                       placeholder="Enter Option 1"
                       required>
            </div>

            <div class="mb-3 option-row">
                <label class="form-label">Option 2</label>
                <input type="text"
                       class="form-control"
                       name="option[]"
                       placeholder="Enter Option 2"
                       required>
            </div>
        `);

        $('#ajaxModel').modal('show');
    });

    // ADD OPTION
    $('#addOption').click(function () {
        optionCount++;

        $('#options').append(`
            <div class="mb-3 option-row">
                <label class="form-label">Option ${optionCount}</label>
                <input type="text"
                       class="form-control"
                       name="option[]"
                       placeholder="Enter Option ${optionCount}"
                       required>
            </div>
        `);
    });

    // SAVE POLL
    $('#savePoll').click(function (e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('poll.store') }}",
            type: "POST",
            data: $('#pollForm').serialize(),
            success: function (data) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Poll created successfully',
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#ajaxModel').modal('hide');
                table.draw();
            }
        });
    });

    // DELETE POLL
    $('body').on('click', '.deletePoll', function () {
        let id = $(this).data('id');

        if (!confirm('Delete this poll?')) return;

        $.ajax({
            url: '/admin/polls/' + id,
            type: 'DELETE',
            success: function () {
                table.draw();
            }
        });
    });

});
</script>

@endsection
