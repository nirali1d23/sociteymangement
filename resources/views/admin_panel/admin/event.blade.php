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

                        <div class="mb-3">
                            <label>Event Title</label>
                            <input type="text" name="event_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Area</label>
                            <input type="text" name="area" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Time</label>
                            <input type="time" name="time" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Day</label>
                            <input type="text" name="day" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Instruction</label>
                            <input type="text" name="instruction" class="form-control" required>
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
</div>

{{-- ✅ INLINE SCRIPT (SAME AS WORKING BLADE) --}}
<script type="text/javascript">
$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Datatable
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

    // ✅ CREATE BUTTON
    $('#createNewProduct').click(function () {
        $('#productForm')[0].reset();
        $('#ajaxModel').modal('show');
    });

    // ✅ SAVE EVENT
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

                $('#productForm')[0].reset();
                $('#ajaxModel').modal('hide');
                table.draw();
            },

            error: function (xhr) {
                console.log(xhr.responseText);
                alert('Something went wrong');
            }
        });
    });

});
</script>

@endsection
