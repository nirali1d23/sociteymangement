@extends('admin_panel.layouts.app')

@section('content')

<div class="pagetitle">
    <h1>Event</h1>
</div>

<div class="card">
    <div class="card-body">
        <br>

        <a class="btn btn-primary float-end" href="javascript:void(0)" id="createNewProduct">
            Create New
        </a>

        <h5 class="card-title">Event List</h5>

        <table class="table table-bordered border-primary data-table">
            <thead>
                <tr>
                    <th>Event Title</th>
                    <th>Date</th>
                    <th>Area</th>
                    <th>Time</th>
                    <th>Day</th>
                    <th>Instruction</th>
                    <th width="200px">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="ajaxModel">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading">Create Event</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="productForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="event_id" id="event_id">

                        <div class="mb-3">
                            <label>Event Title</label>
                            <input type="text" name="event_name" id="event_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Area</label>
                            <input type="text" name="area" id="area" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Date</label>
                            <input type="date" name="date" id="date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Time</label>
                            <input type="time" name="time" id="time" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Day</label>
                            <input type="text" name="day" id="day" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Instruction</label>
                            <input type="text" name="instruction" id="instruction" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <button class="btn btn-primary" id="saveBtn">
                            Save Event
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- Feedback Modal -->
<div class="modal fade" id="feedbackModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Event Feedback</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Feedback</th>
                            <th>Rating</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="feedbackTable"></tbody>
                </table>
            </div>

        </div>
    </div>
</div>

</div>

{{-- ✅ INLINE SCRIPT --}}

<script type="text/javascript">
$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // DATATABLE
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('event') }}",
        columns: [
            { data: 'event_name', name: 'event_name' },
            { data: 'date', name: 'date' },
            { data: 'area', name: 'area' },
            { data: 'time', name: 'time' },
            { data: 'day', name: 'day' },
            { data: 'instruction', name: 'instruction' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // CREATE
    $('#createNewProduct').click(function () {
        $('#productForm')[0].reset();
        $('#event_id').val('');
        $('#modelHeading').html('Create Event');
        $('#ajaxModel').modal('show');
    });

    // EDIT
    $('body').on('click', '.editProduct', function () {
        let id = $(this).data('id');

        $.get("{{ route('event.edit', ':id') }}".replace(':id', id), function (data) {

            $('#modelHeading').html('Edit Event');
            $('#event_id').val(data.id);
            $('#event_name').val(data.event_name);
            $('#area').val(data.area);
            $('#date').val(data.date);
            $('#time').val(data.time);
            $('#day').val(data.day);
            $('#instruction').val(data.instruction);

            $('#ajaxModel').modal('show');
        });
    });

    // SAVE (CREATE + UPDATE)
    $('#saveBtn').click(function (e) {
        e.preventDefault();

        let formData = new FormData($('#productForm')[0]);

        $.ajax({
            url: "{{ route('eventstore') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",

            success: function (data) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message,
                    timer: 3000,
                    showConfirmButton: false
                });

                $('#ajaxModel').modal('hide');
                table.draw();
            }
        });
    });

    // DELETE
    $('body').on('click', '.deleteProduct', function () {

        let id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This event will be deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    type: "DELETE",
                    url: "{{ route('event.delete', ':id') }}".replace(':id', id),

                    success: function (res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        table.draw();
                    }
                });
            }
        });
    });

});
</script>
<script>
$(document).on('click', '.viewFeedback', function () {

    let eventId = $(this).data('id');
    $('#feedbackTable').html('<tr><td colspan="4">Loading...</td></tr>');

    $.get("{{ route('event.feedback', ':id') }}".replace(':id', eventId), function (res) {

        if (res.length === 0) {
            $('#feedbackTable').html(
                '<tr><td colspan="4" class="text-center">No feedback found</td></tr>'
            );
            $('#feedbackModal').modal('show');
            return;
        }

        let html = '';
        res.forEach(row => {
            html += `
                <tr>
                    <td>${row.user_name}</td>
                    <td>${row.feedback}</td>
                    <td>${row.date}</td>
                </tr>
            `;
        });

        $('#feedbackTable').html(html);
        $('#feedbackModal').modal('show');
    });
});
</script>


@endsection
