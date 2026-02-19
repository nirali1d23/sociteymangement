<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="{{ url('/dashboard') }}" class="logo d-flex align-items-center">
            <img src="{{ asset('assets/img/logo.png') }}" alt="">
            <span class="d-none d-lg-block">Society Management</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div>

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0"
                   href="#" data-bs-toggle="dropdown">
                    <span class="d-none d-md-block dropdown-toggle ps-2">ADMIN</span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                    <li class="dropdown-header">
                        <h6>ADMIN</h6>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <!-- CHANGE PASSWORD -->
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2"
                           href="javascript:void(0)"
                           data-bs-toggle="modal"
                           data-bs-target="#changePasswordModal">
                            <i class="bi bi-key"></i>
                            <span>Change Password</span>
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <!-- SIGN OUT -->
                    <li>
                        <form method="POST" action="{{ route('singout') }}">
                            @csrf
                            <button type="submit"
                                class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </button>
                        </form>
                    </li>

                </ul>
            </li>

        </ul>
    </nav>

</header>

<!-- ================= CHANGE PASSWORD MODAL ================= -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="changePasswordForm">
                @csrf

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Current Password</label>
                        <input type="password"
                               class="form-control"
                               name="current_password"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>New Password</label>
                        <input type="password"
                               class="form-control"
                               name="new_password"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Confirm New Password</label>
                        <input type="password"
                               class="form-control"
                               name="new_password_confirmation"
                               required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Update Password
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ================= CHANGE PASSWORD JS ================= -->
<script>
$('#changePasswordForm').on('submit', function (e) {
    e.preventDefault();

    $.ajax({
        url: "{{ route('admin.changePassword') }}",
        type: "POST",
        data: $(this).serialize(),

        success: function (res) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: res.message,
                timer: 2000,
                showConfirmButton: false
            });

            $('#changePasswordModal').modal('hide');
            $('#changePasswordForm')[0].reset();
        },

        error: function (xhr) {
            Swal.fire(
                'Error',
                xhr.responseJSON?.message ?? 'Password update failed',
                'error'
            );
        }
    });
});
</script>
