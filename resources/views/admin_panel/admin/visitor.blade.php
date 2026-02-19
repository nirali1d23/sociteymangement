@extends('admin_panel.layouts.app')

@section('content')

<div class="pagetitle">
    <h1>Visitors</h1>
</div>

<div class="card">
    <div class="card-body">
        <br>

        <table class="table table-bordered border-primary data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Visitor Name</th>
                    <th>Flat No</th>
                    <th>Date</th>
                    <th>Entry Time</th>
                    <th>Exit Time</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

{{-- INLINE SCRIPT --}}
<script type="text/javascript">
$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('visitor') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'visitor_name', name: 'visitor_name' },
            { data: 'flat_no', name: 'flat_no' },
            { data: 'date', name: 'date' },
            { data: 'check_in', name: 'check_in' },
            { data: 'check_out', name: 'check_out' }
        ]
    });

});
</script>

@endsection
