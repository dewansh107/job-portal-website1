@extends('front.layouts.app')

@section('main')
<section class="section-5">
    <div class="container my-5">
        <div class="py-lg-2">&nbsp;</div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Register</h1>
                    <form id="registrationForm" action="{{ route('account.login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="mb-2">Name*</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name" required>
                            <p></p>
                        </div> 
                        <div class="mb-3">
                            <label for="email" class="mb-2">Email*</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email" required>
                            <p></p>
                        </div> 
                        <div class="mb-3">
                            <label for="password" class="mb-2">Password*</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
                            <p></p>
                        </div> 
                        <div class="mb-3">
                            <label for="confirm_password" class="mb-2">Confirm Password*</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Please Confirm Password" required>
                            <p></p>
                        </div> 
                        <button type="submit" class="btn btn-primary mt-2">Register</button>
                    </form>                    
                </div>
                <div class="mt-4 text-center">
                    <p>Have an account? <a href="{{ route('account.login') }}">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('customJs')
<script>
    $(document).ready(function() {
        // Set up CSRF token for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("#registrationForm").submit(function(e){
            e.preventDefault();

            // Clear previous error states
            $(".form-control").removeClass('is-invalid');
            $("p").removeClass('invalid-feedback').html('');

            // Client-side validation for required fields
            var isValid = true;
            $(".form-control[required]").each(function() {
                if ($(this).val() === "") {
                    $(this).addClass('is-invalid');
                    $(this).siblings('p').addClass('invalid-feedback').html('This field is required.');
                    isValid = false;
                }
            });

            // If the form is valid, proceed with AJAX submission
            if (isValid) {
                $.ajax({
                    url: '{{ route("account.processRegistration") }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response){
                        if(response.status === false){
                            var errors = response.errors;
                            // Display validation errors
                            $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.name);
                            $("#email").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.email);
                            $("#password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.password);
                            $("#confirm_password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors.confirm_password);
                        } else {
                            window.location.href = '{{ route("account.login") }}';
                        } 
                    }
                });
            }
        });
    });
</script>
@endsection