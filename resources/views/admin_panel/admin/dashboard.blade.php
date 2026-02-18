@extends('admin_panel.layouts.app')

@section('content')

<div class="pagetitle">
    <h1>Dashboard</h1>
</div>

<div class="row">

    <!-- TOTAL BLOCKS -->
    <div class="col-md-3">
        <div class="card info-card">
            <div class="card-body">
                <h5 class="card-title">Total Blocks</h5>
                <h3>{{ $totalBlocks }}</h3>
            </div>
        </div>
    </div>

    <!-- TOTAL HOUSES -->
    <div class="col-md-3">
        <div class="card info-card">
            <div class="card-body">
                <h5 class="card-title">Total Houses</h5>
                <h3>{{ $totalHouses }}</h3>
            </div>
        </div>
    </div>

    <!-- TOTAL RESIDENTS -->
    <div class="col-md-3">
        <div class="card info-card">
            <div class="card-body">
                <h5 class="card-title">Total Residents</h5>
                <h3>{{ $totalResidents }}</h3>
            </div>
        </div>
    </div>

    <!-- TOTAL STAFF -->
    <div class="col-md-3">
        <div class="card info-card">
            <div class="card-body">
                <h5 class="card-title">Total Staff</h5>
                <h3>{{ $totalStaff }}</h3>
            </div>
        </div>
    </div>

    <!-- TOTAL NOTICES -->
    <div class="col-md-3 mt-3">
        <div class="card info-card">
            <div class="card-body">
                <h5 class="card-title">Total Notices</h5>
                <h3>{{ $totalNotices }}</h3>
            </div>
        </div>
    </div>

</div>

@endsection
