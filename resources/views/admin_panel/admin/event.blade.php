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
</div>

<!-- Modal -->
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="productForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" id="product_id">

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
                        <input type="file" name="image" id="image" class="form-control">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary" id="saveBtn">
                            Save Event
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
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

    // Open modal
    $('#createNewProduct').click(function () {
        $('#productForm')[0].reset();
        $('#product_id').val('');
        $('#modelHeading').html("Create New Event");
        $('#ajaxModel').modal('show');
    });

    // Store event (FIXED)
    $('#productForm').submit(function (e) {
        e.preventDefault();

        $('#saveBtn').html('Saving...');

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('eventstore') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function (res) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: res.message,
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#ajaxModel').modal('hide');
                table.draw();
                $('#saveBtn').html('Save Event');
            },

            error: function (xhr) {
                console.log(xhr.responseText);
                alert('Something went wrong');
                $('#saveBtn').html('Save Event');
            }
        });
    });

    // Delete
    $('body').on('click', '.deleteProduct', function () {
        let id = $(this).data('id');

        if (!confirm("Are you sure?")) return;

        $.ajax({
            type: "DELETE",
            url: "{{ route('userdelete', ':id') }}".replace(':id', id),
            success: function () {
                table.draw();
            }
        });
    });

});
</script>
@endpush
