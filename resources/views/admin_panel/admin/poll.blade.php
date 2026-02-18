@extends('admin_panel.layouts.app')

@section('content')

<div class="pagetitle">
    <h1>Poll</h1>
</div>

<div class="card">
    <div class="card-body">
        <br>

        <a class="btn btn-primary float-end" href="javascript:void(0)" id="createNewPoll">
            Create New
        </a>

        <h5 class="card-title">Poll List</h5>

        <table class="table table-bordered border-primary data-table">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Options</th>
                    <th>Votes</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="ajaxModel">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Create Poll</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="pollForm">

                        <div class="mb-3">
                            <label>Question</label>
                            <input type="text" class="form-control" name="question" required>
                        </div>

                        <div id="options">
                            <div class="mb-2">
                                <input type="text" class="form-control" name="option[]" placeholder="Option 1" required>
                            </div>
                            <div class="mb-2">
                                <input type="text" class="form-control" name="option[]" placeholder="Option 2" required>
                            </div>
                        </div>

                        <button type="button" class="btn btn-success btn-sm" id="addOption">
                            Add Option
                        </button>

                        <br><br>

                        <button class="btn btn-primary" id="savePoll">Save</button>

                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ✅ INLINE SCRIPT (JUST LIKE ALLOTMENT) --}}
<script type="text/javascript">
$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // DataTable
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('poll.data') }}",
        columns: [
            { data: 'question' },
            { data: 'options_count' },
            { data: 'votes_count' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    // OPEN MODAL (THIS IS THE FIX)
    $('#createNewPoll').click(function () {
        $('#pollForm')[0].reset();
        $('#options').html(`
            <input type="text" class="form-control mb-2" name="option[]" required>
            <input type="text" class="form-control mb-2" name="option[]" required>
        `);
        $('#ajaxModel').modal('show');
    });

    // ADD OPTION
    $('#addOption').click(function () {
        $('#options').append(
            `<input type="text" class="form-control mb-2" name="option[]" required>`
        );
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
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#ajaxModel').modal('hide');
                table.draw();
            }
        });
    });

    // DELETE
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
