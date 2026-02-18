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
                    <h4 class="modal-title">Create Allotment</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="productForm">

                        <div class="mb-3">
                            <label>User</label>
                            <select class="form-select" name="user_id" required>
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Flat</label>
                            <select class="form-select" name="flat_id" required>
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

    // DATATABLE (NO ACTION COLUMN)
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('alltoment') }}",
        columns: [
            { data: 'user_name', name: 'user_name' },
            { data: 'flat_number', name: 'flat_number' }
        ]
    });

    // OPEN MODAL
    $('#createNewProduct').click(function () {
        $('#productForm')[0].reset();
        $('#ajaxModel').modal('show');
    });

    // SAVE (CREATE ONLY)
    $('#saveBtn').click(function (e) {
        e.preventDefault();

        $.post("{{ route('allotment.store') }}", $('#productForm').serialize(), function () {
            $('#ajaxModel').modal('hide');
            table.draw();
        });
    });

});
</script>
@endsection
