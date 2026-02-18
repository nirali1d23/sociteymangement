@extends('admin_panel.layouts.app')

@section('content')

<div class="pagetitle">
    <h1>Flat</h1>
</div>

<div class="card">
<div class="card-body mt-4">

<table class="table table-bordered data-table">
<thead>
<tr>
    <th>FlatNumber</th>
    <th>FloorNumber</th>
    <th>BlockNumber</th>
    <th>Action</th>
</tr>
</thead>
<tbody></tbody>
</table>

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

    $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('flat.index') }}",
        columns: [
            { data: 'flat_number', name: 'flat_number' },
            { data: 'floor_number', name: 'floor_number' },
            { data: 'block_number', name: 'block_number' },
            { data: 'action', orderable: false, searchable: false }
        ]
    });

});
</script>
@endpush
