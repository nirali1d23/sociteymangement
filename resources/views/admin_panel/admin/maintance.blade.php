@extends('admin_panel.layouts.app')

@section('content')

<div class="pagetitle">
    <h1>Maintenance</h1>
</div>

<div class="card">
    <div class="card-body">
        <br>

        <h5 class="card-title">Maintenance Requests</h5>

        <table class="table table-bordered border-primary data-table">
            <thead>
                <tr>
                    <th>User Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th width="220px">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- ================= Assign Staff Modal ================= -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Assign Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="maintance_id">

                <div class="mb-3">
                    <label class="form-label">Select Staff</label>
                    <select class="form-control" id="staff_id">
                        <option value="">Select Staff</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="assignBtn">Assign</button>
            </div>

        </div>
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

    /* ================= DataTable ================= */
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('maintance') }}",
        columns: [
            { data: 'user_name', name: 'user_name' },
            { data: 'description', name: 'description' },
            { data: 'status_text', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    /* ================= Open Assign Modal ================= */
    $('body').on('click', '.assignStaff', function () {

        let maintanceId = $(this).data('id');
        $('#maintance_id').val(maintanceId);
        $('#staff_id').html('<option>Loading...</option>');

        $.get("{{ route('staff.list') }}", function (res) {
            let options = '<option value="">Select Staff</option>';
            res.forEach(function (staff) {
                options += `<option value="${staff.id}">${staff.name}</option>`;
            });
            $('#staff_id').html(options);
            $('#assignModal').modal('show');
        });
    });

    /* ================= Assign Staff ================= */
    $('#assignBtn').click(function () {

        let staffId = $('#staff_id').val();
        let maintanceId = $('#maintance_id').val();

        if (!staffId) {
            alert('Please select staff');
            return;
        }

        $.ajax({
            url: "{{ route('maintance.assign') }}",
            type: "POST",
            data: {
                maintance_id: maintanceId,
                staff_id: staffId,
                status: 1
            },
            success: function (res) {
                $('#assignModal').modal('hide');
                table.draw();
            }
        });
    });

    /* ================= Delete ================= */
    $('body').on('click', '.deleteProduct', function () {

        let id = $(this).data('id');

        if (!confirm('Are you sure?')) return;

        $.ajax({
            type: "DELETE",
            url: "{{ route('userdelete', ':id') }}".replace(':id', id),
            success: function () {
                table.draw();
            }
        });
    });

});
</script>
@endpush
