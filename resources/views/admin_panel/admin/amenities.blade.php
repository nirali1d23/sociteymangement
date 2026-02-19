@extends('admin_panel.layouts.app')

@section('content')

<div class="pagetitle">
    <h1>Amenities</h1>
</div>

<div class="card">
    <div class="card-body">
        <br>

        <a class="btn btn-primary float-end" href="javascript:void(0)" id="createNewProduct">
            Create New
        </a>

        <h5 class="card-title">Amenities List</h5>

        <table class="table table-bordered border-primary data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Amenities</th>
                    <th>Rule</th>
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
                    <h4 class="modal-title" id="modelHeading">Create Amenities</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="productForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="product_id" id="product_id">

                        <div class="mb-3">
                            <label>Amenities Name</label>
                            <input type="text" name="amenities" id="amenities" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Rule</label>
                            <input type="text" name="rule" id="rule" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <button class="btn btn-primary" id="saveBtn">
                            Save Amenities
                        </button>
                    </form>
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
        ajax: "{{ route('amenities') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'amenities_name', name: 'amenities_name' },
            { data: 'rule', name: 'rule' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // CREATE
    $('#createNewProduct').click(function () {
        $('#productForm')[0].reset();
        $('#product_id').val('');
        $('#modelHeading').html('Create Amenities');
        $('#ajaxModel').modal('show');
    });

    // EDIT
    $('body').on('click', '.editProduct', function () {
        let id = $(this).data('id');

        $.get("{{ route('amenities.edit', ':id') }}".replace(':id', id), function (data) {
            $('#modelHeading').html('Edit Amenities');
            $('#product_id').val(data.id);
            $('#amenities').val(data.amenities_name);
            $('#rule').val(data.rule);
            $('#ajaxModel').modal('show');
        });
    });

    // SAVE (CREATE + UPDATE)
    $('#saveBtn').click(function (e) {
        e.preventDefault();

        let formData = new FormData($('#productForm')[0]);

        $.ajax({
            url: "{{ route('amenities.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",

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
            }
        });
    });

    // DELETE
    $('body').on('click', '.deleteProduct', function () {

        let id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This amenities will be deleted!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {

            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('amenities.delete', ':id') }}".replace(':id', id),

                    success: function (res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: res.message,
                            timer: 1500,
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

@endsection
