@extends('layouts.public')

@section('title', 'Contact Us')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="page-card">
            <h2 class="page-title">Contact Us</h2>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ url('/contact-us') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Message</label>
                    <textarea name="message" rows="4" class="form-control" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Send Message
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
