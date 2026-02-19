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
                    <th width="200px">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Assign Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Assign Staff</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="maintance_id">

        <label class="form-label">Select Staff</label>
        <select id="staff_id" class="form-control">
            <option value="">Select Staff</option>
        </select>
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

    /* DataTable */
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

    /* Open Assign Modal */
    $('body').on('click', '.assignStaff', function () {

        $('#maintance_id').val($(this).data('id'));
        $('#staff_id').html('<option>Loading...</option>');

        $.get("{{ route('staff.list') }}", function (res) {

            let html = '<option value="">Select Staff</option>';
            res.forEach(staff => {
                html += `<option value="${staff.id}">${staff.name}</option>`;
            });

            $('#staff_id').html(html);
            $('#assignModal').modal('show');
        });
    });

    /* Assign */
    $('#assignBtn').click(function () {

        let staffId = $('#staff_id').val();

        if (!staffId) {
            alert('Please select staff');
            return;
        }

        $.post("{{ route('maintance.assign') }}", {
            maintance_id: $('#maintance_id').val(),
            staff_id: staffId,
            status: 1
        }, function () {
            $('#assignModal').modal('hide');
            table.draw(false);
        });
    });

});
</script>
@endpush
