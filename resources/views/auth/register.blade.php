<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('loginAssets/images/icons/favicon.ico')}}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('loginAssets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('loginAssets/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('loginAssets/vendor/animate/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('loginAssets/vendor/css-hamburgers/hamburgers.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('loginAssets/vendor/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('loginAssets/css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('loginAssets/css/main.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .invalid-feedback{
            display: block;
        }
    </style>
</head>
<body>

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <div class="login100-pic js-tilt" data-tilt>
                <img src="{{ asset('loginAssets/images/img-01.png') }}" alt="IMG">
            </div>

            <form class="login100-form validate-form">
                <span class="login100-form-title">
                    Register Form
                </span>

                <div class="wrap-input100 validate-input" data-validate = "Name is required">
                    <input class="input100 @error('name') is-invalid @enderror" id="name" type="text" name="name" placeholder="Name"  value="{{ old('name') }}" required autocomplete="name" autofocus>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-user-circle" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
                    <input class="input100 @error('email') is-invalid @enderror" id="email" type="email" name="email" placeholder="Email"  value="{{ old('email') }}" required autocomplete="email" autofocus>
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-envelope" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="wrap-input100 validate-input" data-validate = "Password is required">
                    <input class="input100 @error('password') is-invalid @enderror" type="password" id="password" name="password" placeholder="Password" required autocomplete="new-password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="wrap-input100 validate-input" data-validate = "Password confirmation is required">
                    <input class="input100 @error('password-confirm') is-invalid @enderror" type="password" id="password-confirm" name="password_confirmation" placeholder="Password Confirmation" required autocomplete="new-password">
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock" aria-hidden="true"></i>
                    </span>
                </div>

                <div class="container-login100-form-btn">
                    <button class="login100-form-btn" id="btn-submit">
                        Register
                    </button>
                </div>

                <div class="text-center p-t-136">
                    <a class="txt2" href="{{ route('login') }}">
                        Have an Account
                        <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="{{ asset('loginAssets/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('loginAssets/vendor/bootstrap/js/popper.js') }}"></script>
<script src="{{ asset('loginAssets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('loginAssets/vendor/select2/select2.min.js') }}"></script>
<script src="{{ asset('loginAssets/vendor/tilt/tilt.jquery.min.js') }}"></script>
<script >
    $('.js-tilt').tilt({
        scale: 1.1
    })
</script>
<script src="{{ asset('loginAssets/js/main.js') }}"></script>
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $("#btn-submit").on('click', function(e){
        e.preventDefault();
        var password = $("input[name=password]").val();
        var email = $("input[name=email]").val();
        var name = $("input[name=name]").val();
        var password_confirmation = $("input[name=password_confirmation]").val();

        $.ajax({
            type:'POST',
            url:"{{ route('authController.register') }}",
            data:{
                password:password,
                email:email,
                name:name,
                password_confirmation:password_confirmation,
            },
            success:function(data){
                console.log(data);
                if(data['status'] === 'success'){
                    $('#btn-submit').after("<div class='alert alert-success mt-3'>User Created successfully</div>");
                    window.location.href = "{{ route('login') }}"
                }
            },
            error:function (data){
                if(data['status'] === 422){
                    var erroJson = JSON.parse(data.responseText);
                    //CLEAR ALL THE PREVIOUS ERRORS
                    for (var err in erroJson) {
                        for (var errstr of erroJson[err])
                            $("[name='" + err + "']").after("<div class='alert alert-danger'>" + errstr + "</div>");
                    }
                } else{
                    var erroJson = JSON.parse(data.responseText);
                    //CLEAR ALL THE PREVIOUS ERRORS
                    $('#btn-submit').after("<div class='alert alert-danger'>" + erroJson + "</div>");
                }
            },
        });

    });
</script>
</body>
</html>
