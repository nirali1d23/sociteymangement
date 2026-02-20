@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4" style="width: 350px">
        <h5 class="text-center mb-3">Enter Security PIN</h5>

        <form method="POST" action="{{ route('security.pin.verify') }}">
            @csrf

            <div class="d-flex justify-content-between mb-3">
                <input type="password" maxlength="1" class="form-control text-center pin-box" name="pin[]">
                <input type="password" maxlength="1" class="form-control text-center pin-box" name="pin[]">
                <input type="password" maxlength="1" class="form-control text-center pin-box" name="pin[]">
                <input type="password" maxlength="1" class="form-control text-center pin-box" name="pin[]">
            </div>

            @error('pin')
                <div class="text-danger text-center mb-2">{{ $message }}</div>
            @enderror

            <button class="btn btn-primary w-100">Verify PIN</button>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.pin-box {
    width: 50px;
    height: 50px;
    font-size: 22px;
}
</style>
@endpush