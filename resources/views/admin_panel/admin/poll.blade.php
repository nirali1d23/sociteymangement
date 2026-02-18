@extends('admin_panel.layouts.app')

@section('content')

<div class="pagetitle">
    <h1>Poll</h1>
</div>

<div class="card">
    <div class="card-body">
        <br>
        <button class="btn btn-primary float-end" id="createPoll">Create New</button>

        <table class="table table-bordered data-table mt-3">
            <thead>
                <tr>
                    <th>Question</th>
                    <th>Options</th>
                    <th>Votes</th>
                    <th width="120px">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="pollModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Create New Poll</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="pollForm">
                @csrf
                <div class="modal-body">

                    <label>Question</label>
                    <input type="text" name="question" class="form-control mb-2" required>

                    <div id="options">
                        <input type="text" name="option[]" class="form-control mb-2" placeholder="Option 1" required>
                        <input type="text" name="option[]" class="form-control mb-2" placeholder="Option 2" required>
                    </div>

                    <button type="button" class="btn btn-success btn-sm" id="addOption">
                        Add Option
                    </button>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Save Poll</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script>
$(function () {

    let table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.polls.list') }}",
        columns: [
            { data: 'question' },
            { data: 'options_count' },
            { data: 'votes_count' },
            { data: 'action', orderable: false, searchable: false },
        ]
    });

    $('#createPoll').click(() => {
        $('#pollForm')[0].reset();
        $('#pollModal').modal('show');
    });

    $('#addOption').click(() => {
        $('#options').append(
            `<input type="text" name="option[]" class="form-control mb-2" required>`
        );
    });

    $('#pollForm').submit(function(e){
        e.preventDefault();

        $.post("{{ route('admin.polls.store') }}", $(this).serialize(), () => {
            $('#pollModal').modal('hide');
            table.draw();
        });
    });

    $('body').on('click', '.deletePoll', function(){
        if(!confirm('Delete this poll?')) return;

        $.ajax({
            url: "{{ url('admin/polls') }}/" + $(this).data('id'),
            type: 'DELETE',
            data: {_token: "{{ csrf_token() }}"},
            success: function(){
                table.draw();
            }
        });
    });

});
</script>
@endpush
