@extends('layouts.public')

@section('title', 'Contact Us')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="page-card">

            {{-- AFTER SUBMIT --}}
            @if(session('submitted'))
                <div class="text-center">
                    <h2 class="page-title text-success">
                        Message Sent Successfully
                    </h2>

                    <p class="mt-3">
                        Thank you for contacting us.  
                        Our team will get back to you shortly.
                    </p>

                    <a href="{{ route('contact') }}" class="btn btn-primary mt-3">
                        Send Another Message
                    </a>
                </div>

            {{-- BEFORE SUBMIT (FORM) --}}
            @else
                <h2 class="page-title">Contact Us</h2>

                <form method="POST" action="{{ route('contact.submit') }}">
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
            @endif

        </div>
    </div>
</div>
@endsection
