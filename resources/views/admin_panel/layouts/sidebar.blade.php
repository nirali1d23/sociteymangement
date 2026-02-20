<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">


      <li class="nav-item">
        <a class="nav-link collapsed" href="{{route('admin.dashboard')}}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link  @if(Request::segment(1) != 'add-residenet') collapsed @endif " href="{{route('add-residenet')}}">
          <i class="ri-account-circle-line"></i>
          <span>User</span>
        </a>
      </li>

      {{-- <li class="nav-item"> 
        <a class="nav-link  @if(Request::segment(1) != 'flate') collapsed @endif " href="{{route('flate')}}">
          <i class="bi bi-shop"></i>
          <span>Flat</span>
        </a>
      </li> --}}

      <li class="nav-item">
   
        <a class="nav-link  @if(Request::segment(1) != 'alltoment') collapsed @endif " href="{{route('alltoment')}}">
          <i class="bi bi-check-circle"></i>
          <span>Alltoment</span>
        </a>
      </li>


      
      
       <li class="nav-item">
        <a class="nav-link collapsed" href="{{route('notice')}}">
          <i class="ri-apps-2-line"></i>
          <span>Notice</span>
        </a>
      </li>
        <li class="nav-item">
      </li>
        <a class="nav-link collapsed" href="{{route('poll.index')}}">
          <i class="ri-bar-chart-fill"></i>
          <span>Poll</span>
        </a>


         <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#eam-nav" data-bs-toggle="collapse" href="#">
          <i class="ri-account-circle-line"></i><span>Manage EAM </span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="eam-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{route('event')}}">
              <i class="bi bi-circle"></i><span>Event</span>
            </a>
          </li>
         
          <li>
            <a href="{{route('maintance')}}">
              <i class="bi bi-circle"></i><span>Maintance</span>
            </a>
          </li>
         
        </ul>
      </li>




      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#amenities-nav" data-bs-toggle="collapse" href="#">
          <i class="ri-bank-fill"></i>
          <span>Amenities Mangement</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="amenities-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li class="nav-item">
            <a class="nav-link collapsed" href="{{route('amenities')}}">
              <i class="bi bi-circle"></i><span>Amenities</span>
            </a>
          </li>
          <li>
            <a href="{{route('bookamenities')}}">
              <i class="bi bi-circle"></i><span>Bookings</span>
            </a>
          </li>
         
         
        </ul>
      </li>





      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#visitor-nav" data-bs-toggle="collapse" href="#">
          <i class="ri-admin-line"></i>
          <span>Visitor Mangement</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="visitor-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li class="nav-item">
            <a class="nav-link collapsed" href="{{route('visitor')}}">
              <i class="bi bi-circle"></i><span>Visitor Entry</span>
            </a>
          </li>
          <li>
            <a href="{{route('previsitor')}}">
              <i class="bi bi-circle"></i><span>Approve Visitor</span>
            </a>
          </li>
         
         
        </ul>
      </li>


<li class="nav-item">
    <a class="nav-link collapsed"
       data-bs-target="#setting-nav"
       data-bs-toggle="collapse"
       href="#">
               <i class="bi bi-gear"></i>

        <span>Setting</span>
    </a>

    <ul id="setting-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
        <li>
            <a href="javascript:void(0)"
               data-bs-toggle="modal"
               data-bs-target="#changePasswordModal">
                <i class="bi bi-circle"></i>
                <span>Change Password</span>
            </a>
        </li>

        <li>
            <a href="javascript:void(0)"
               data-bs-toggle="modal"
               data-bs-target="#changePinModal">
                <i class="bi bi-circle"></i>
                <span>Change Security PIN</span>
            </a>
        </li>
    </ul>
</li>
         
        </ul>
      </li>
















{{-- 
      <li class="nav-item">
        <a class="nav-link collapsed" href="users-profile.html">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li> --}}
     
    </ul>

 

  </aside><!-- End Sidebar-->