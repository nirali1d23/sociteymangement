@extends('admin_panel.layouts.app')

@section('content')

<div class="pagetitle">
    <h1>Allotment</h1>
</div>

<div class="card">
    <div class="card-body">
        <br>

        <a class="btn btn-primary float-end" href="javascript:void(0)" id="createNewProduct">
            Create New
        </a>

        <h5 class="card-title">Bordered Table</h5>

        <table class="table table-bordered border-primary data-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Flat No</th>
                    <th width="280px">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="ajaxModel" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Create / Edit Allotment</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="productForm">
                        <input type="hidden" name="product_id" id="product_id">

                        <div class="mb-3">
                            <label>User</label>
                            <select class="form-select" name="user_id">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Flat</label>
                            <select class="form-select" name="flat_id">
                                <option value="">Select Flat</option>
                                @foreach($flats as $flat)
                                    <option value="{{ $flat->id }}">{{ $flat->flat_number }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" id="saveBtn">
                            Save
                        </button>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
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
        ajax: "{{ route('alltoment') }}",
        columns: [
            { data: 'user_name', name: 'user_name' },
            { data: 'flat_number', name: 'flat_number' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

    // CREATE
    $('#createNewProduct').click(function () {
        $('#productForm')[0].reset();
        $('#product_id').val('');
        $('#ajaxModel').modal('show');
    });

    // EDIT
    // $('body').on('click', '.editProduct', function () {
    //     let id = $(this).data('id');

    //     $.get("{{ route('allotment.edit', ':id') }}".replace(':id', id), function (data) {
    //         $('#product_id').val(data.id);
    //         $('select[name="user_id"]').val(data.user_id);
    //         $('select[name="flat_id"]').val(data.flat_id);
    //         $('#ajaxModel').modal('show');
    //     });
    // });

    // SAVE
    $('#saveBtn').click(function (e) {
        e.preventDefault();

        $.post("{{ route('allotment.store') }}", $('#productForm').serialize(), function () {
            $('#ajaxModel').modal('hide');
            table.draw();
        });
    });

    // DELETE
    $('body').on('click', '.deleteProduct', function () {
        if (!confirm('Are you sure?')) return;

        let id = $(this).data('id');

        $.ajax({
            type: 'DELETE',
            url: "{{ route('allotment.delete', ':id') }}".replace(':id', id),
            success: function () {
                table.draw();
            }
        });
    });

});
</script>
@endsection
