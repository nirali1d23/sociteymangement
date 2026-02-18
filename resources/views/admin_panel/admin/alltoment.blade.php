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
                    <th>Block No</th>
                    <th>House No</th>
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
                    <h4 class="modal-title">Create Allotment</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="productForm">
                        <div class="mb-3">
                            <label>User</label>
                            <select class="form-select" name="user_id">
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    <div class="mb-3">
                        <label>Block</label>
                        <select class="form-select" id="flat_id">
                            <option value="">-- Select Block --</option>
                            @foreach($flats as $flat)
                                <option value="{{ $flat->id }}">{{ $flat->block_no }}</option>
                            @endforeach
                        </select>
                    </div>

                <div class="mb-3">
    <label>House</label>
    <select class="form-select" name="house_id" id="house_id">
        <option value="">-- Select House --</option>
    </select>
</div>


                        <button class="btn btn-primary" id="saveBtn">Save</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ✅ INLINE SCRIPT (IMPORTANT) --}}
<script type="text/javascript">
$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('alltoment.data') }}",
        columns: [
            { data: 'user_name', name: 'user_name' },
            { data: 'block_number', name: 'block_number' },
            { data: 'house_number', name: 'house_number' }
        ]
    });

    $('#flat_id').change(function () {
    let flatId = $(this).val();
    $('#house_id').html('<option value="">Loading...</option>');

    if (flatId) {
        $.get('/admin/get-houses/' + flatId, function (data) {
            let options = '<option value="">-- Select House --</option>';
            data.forEach(function (house) {
                options += `<option value="${house.id}">${house.house_number}</option>`;
            });
            $('#house_id').html(options);
        });
    } else {
        $('#house_id').html('<option value="">-- Select House --</option>');
    }
});


    $('#createNewProduct').click(function () {
        $('#productForm')[0].reset();
        $('#ajaxModel').modal('show');
    });

$('#saveBtn').click(function (e) {
    e.preventDefault();

    $.ajax({
        data: $('#productForm').serialize(),
        url: "{{ route('allotment.store') }}",
        type: "POST",
        dataType: "json",

        success: function (data) {
            if (data.success) {
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
            }
        }
    });
});


});
</script>

@endsection
