@extends('admin_panel.layouts.app')

@section('content')

<div class="pagetitle">
    <h1>Allotment</h1>
</div>

<div class="card">
<div class="card-body mt-3">

<button class="btn btn-primary float-end" id="createNewProduct">Create New</button>

<table class="table table-bordered data-table mt-4">
<thead>
<tr>
    <th>User</th>
    <th>House</th>
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
<h5 class="modal-title">Create Allotment</h5>
<button class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
<form id="productForm">

<!-- BLOCK -->
<div class="mb-3">
<label>Block</label>
<select class="form-select" id="block_id">
<option value="">Select Block</option>
@foreach($blocks as $block)
<option value="{{ $block->id }}">{{ $block->block_no }}</option>
@endforeach
</select>
</div>

<!-- HOUSE -->
<div class="mb-3">
<label>House</label>
<select class="form-select" name="flat_id" id="house_id">
<option value="">Select House</option>
</select>
</div>

<!-- USER -->
<div class="mb-3">
<label>User</label>
<select class="form-select" name="user_id">
<option value="">Select User</option>
@foreach($users as $user)
<option value="{{ $user->id }}">{{ $user->name }}</option>
@endforeach
</select>
</div>

<button class="btn btn-primary" id="saveBtn">Save</button>
</form>
</div>

</div>
</div>
</div>

@endsection
<script>
$(function () {

    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('alltoment') }}",
        columns: [
            {data: 'user_name'},
            {data: 'flat_number'}
        ]
    });

    $('#createNewProduct').click(function () {
        $('#productForm')[0].reset();
        $('#ajaxModel').modal('show');
    });

    // 🔹 Load houses by block
    $('#block_id').change(function(){
        let blockId = $(this).val();
        $('#house_id').html('<option>Loading...</option>');

        $.get("{{ url('admin/get-houses') }}/" + blockId, function(data){
            let options = '<option value="">Select House</option>';
            data.forEach(function(house){
                options += `<option value="${house.id}">${house.house_number}</option>`;
            });
            $('#house_id').html(options);
        });
    });

    // 🔹 Store allotment
    $('#saveBtn').click(function(e){
        e.preventDefault();

        $.post("{{ route('allotment.store') }}", $('#productForm').serialize(), function(){
            $('#ajaxModel').modal('hide');
            table.draw();
        });
    });

});
</script>
