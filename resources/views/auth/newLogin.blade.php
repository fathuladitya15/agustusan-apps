<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        {{-- <a href="#"><b>HR</b>Management</a> --}}
        <a href="#"><b>L</b>ogin</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">

            <form method="POST" action="{{ route('login') }}" id="formLogin">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="username" name="username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-key"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Password" name="password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                <!-- /.col -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block" id="button_login">Masuk
                    </button>
                </div>
                <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

@vite(['resources/sass/app.scss', 'resources/js/app.js'])

<script>
    $("#formLogin").submit(function(e) {
        var button = document.getElementById('button_login');
        var loading = '<span class="visually-hidden">Loading...</span><div class="spinner-border spinner-border-sm" role="status"></div>';
        e.preventDefault();
        $.ajax({
            url : $(this).attr('action'),
            data: $(this).serialize(),
            type: $(this).attr('method'),
            beforeSend: function() {
                button.innerHTML = loading;
                button.disabled  = true;
            },
            success: function(s) {
                window.location = '/home';
            },error: function(xhr) {
                var err = xhr.responseJSON;
                $.each(err.errors, function(key, value) {
                    iziToast.error({
                        title: 'Error',
                        message: value,
                        position: 'topRight'
                    });
                });
            },complete: function() {
                button.innerHTML = "Masuk";
                button.disabled  = false;
            }
        })
    })
</script>
</body>
</html>
