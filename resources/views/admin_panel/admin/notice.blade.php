@extends('admin_panel.layouts.app')

@section('content')

<style>
.hidden-fields {
    display: none;
}
</style>

<div class="pagetitle">
    <h1>Notice</h1>
</div>

<div class="card">
    <div class="card-body">
        <br>

        <a class="btn btn-primary float-end" href="javascript:void(0)" id="createNewProduct">
            Create New
        </a>

        <h5 class="card-title">Notice List</h5>

        <table class="table table-bordered border-primary data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th width="200px">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="ajaxModel">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <label>Schedule Notice</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="toggle">
                    </div>
                </div>

                <form id="productForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="notice_id" id="notice_id">

                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" class="form-control" name="title" id="title" required>
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <input type="text" class="form-control" name="description" id="description" required>
                    </div>

                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" class="form-control" name="image" id="image">
                    </div>

                    <div id="moreFields" class="hidden-fields">
                        <div class="mb-3">
                            <label>Start Date</label>
                            <input type="date" class="form-control" name="start_date" id="start_date">
                        </div>

                        <div class="mb-3">
                            <label>Time</label>
                            <input type="time" class="form-control" name="time" id="time">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" id="saveBtn">
                        Save Notice
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>

{{-- JS --}}
<script type="text/javascript">
$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Toggle schedule fields
    $('#toggle').change(function () {
        $('#moreFields').toggle(this.checked);
    });

    // Datatable
    let table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('notice') }}",
        columns: [
            { data: 'title', name: 'title' },
            { data: 'description', name: 'description' },
            {
                data: 'image',
                name: 'image',
                render: function (data) {
                    if (data) {
                        return `<img src="/${data}" width="60" class="rounded">`;
                    }
                    return '-';
                }
            },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    // CREATE
    $('#createNewProduct').click(function () {
        $('#productForm')[0].reset();
        $('#notice_id').val('');
        $('#toggle').prop('checked', false);
        $('#moreFields').hide();
        $('#modelHeading').html('Create Notice');
        $('#ajaxModel').modal('show');
    });

    // EDIT
    $('body').on('click', '.editProduct', function () {
        let id = $(this).data('id');

        $.get("{{ route('notice.edit', ':id') }}".replace(':id', id), function (data) {
            $('#modelHeading').html('Edit Notice');
            $('#notice_id').val(data.id);
            $('#title').val(data.title);
            $('#description').val(data.description);
            $('#start_date').val(data.start_date);
            $('#time').val(data.time);

            if (data.start_date || data.time) {
                $('#toggle').prop('checked', true);
                $('#moreFields').show();
            }

            $('#ajaxModel').modal('show');
        });
    });

    // SAVE (CREATE + UPDATE) ✅ FormData for image
    $('#productForm').submit(function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('noticestore') }}",
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
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                Swal.fire('Error', 'Something went wrong', 'error');
            }
        });
    });

    // DELETE
    $('body').on('click', '.deleteProduct', function () {
        let id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This notice will be deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('notice.delete', ':id') }}".replace(':id', id),
                    success: function () {
                        Swal.fire('Deleted!', 'Notice removed', 'success');
                        table.draw();
                    }
                });
            }
        });
    });

});
</script>

@endsection
