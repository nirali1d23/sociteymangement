@extends('admin_panel.layouts.app')
@section('content')

<div class="pagetitle">
    <h1>Pre Visitor</h1>
</div>

<div class="card">
    <div class="card-body">
        <br>

        <h5 class="card-title">Pre Visitor List</h5>

        <table class="table table-bordered border-primary data-table">
            <thead>
                <tr>
                    <th>Visitor Name</th>
                    <th>Date</th>
                    <th>Flat No</th>
                    <th>No of Person</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('previsitor') }}",
        columns: [
            { data: 'visitor_name', name: 'visitor_name' },
            { data: 'date', name: 'date' },
            { data: 'flat_no', name: 'flat_no' },
            { data: 'contact_number', name: 'contact_number' },
            {
                data: 'status',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    if (data == 0) {
                        return `<button class="btn btn-danger btn-sm toggle-status"
                                data-id="${row.id}" data-status="0">
                                Not Approved
                                </button>`;
                    } else {
                        return `<button class="btn btn-success btn-sm toggle-status"
                                data-id="${row.id}" data-status="1">
                                Approved
                                </button>`;
                    }
                }
            }
        ]
    });

    // ✅ STATUS TOGGLE WITH SWEETALERT
    $(document).on('click', '.toggle-status', function () {

        let id = $(this).data('id');
        let status = $(this).data('status');
        let newStatus = status == 0 ? 1 : 0;

        let actionText = status == 0 ? 'approve' : 'disapprove';
        let btnColor = status == 0 ? '#28a745' : '#d33';

        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to ${actionText} this visitor?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: btnColor,
            confirmButtonText: `Yes, ${actionText}!`,
            cancelButtonText: 'Cancel'
        }).then((result) => {

            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('previsitor.updateStatus') }}",
                    type: "POST",
                    data: {
                        id: id,
                        status: newStatus
                    },
                    success: function () {
                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: `Visitor ${actionText}ed successfully`,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        table.ajax.reload(null, false);
                    },
                    error: function () {
                        Swal.fire('Error', 'Status update failed', 'error');
                    }
                });
            }
        });
    });

});
</script>

@endsection
