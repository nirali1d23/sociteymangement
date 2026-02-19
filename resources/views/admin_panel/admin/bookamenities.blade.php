@extends('admin_panel.layouts.app')

@section('content')

<div class="pagetitle">
    <h1>Booked Amenities</h1>
</div>

<div class="card">
    <div class="card-body">
        <br>

        <h5 class="card-title">Booked Amenities List</h5>

        <table class="table table-bordered border-primary data-table">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Amenities</th>
                    <th>Date</th>
                    <th>Status</th>
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

    let table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('bookamenities') }}",
        columns: [
            { data: 'user_name', name: 'user_name' },
            { data: 'amenities_name', name: 'amenities_name' },
            { data: 'date', name: 'date' },
            {
                data: 'status',
                name: 'status',
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

    // STATUS TOGGLE
$(document).on('click', '.toggle-status', function () {

    let id = $(this).data('id');
    let status = $(this).data('status');
    let newStatus = status == 0 ? 1 : 0;

    let actionText = status == 0 ? 'approve' : 'disapprove';
    let buttonColor = status == 0 ? '#28a745' : '#d33';

    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to ${actionText} this booking?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: buttonColor,
        confirmButtonText: `Yes, ${actionText}!`,
        cancelButtonText: 'Cancel'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "{{ route('updateAmenityStatus') }}",
                type: "POST",
                data: {
                    id: id,
                    status: newStatus
                },
                success: function () {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: `Booking ${actionText}ed successfully`,
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
