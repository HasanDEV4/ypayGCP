<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script>
  <script>
    WebFont.load({
      google: {
        families: ['Poppins:400']
      }
    });
  </script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>YPay</title>
  <link rel="stylesheet" href="{{asset('/css/adminlte.min.css')}}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/images/apple-touch-icon.png')}}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/images/favicon-32x32.png')}}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/images/favicon-16x16.png')}}">
  <script src="https://kit.fontawesome.com/10af330120.js" crossorigin="anonymous"></script>


<style>
  html{
    font-size: 14px !important;
  }
  body {
    font-family: 'Poppins', sans-serif;
    overflow-x: hidden;
    background-repeat: no-repeat;
    background-size: cover;
    height: 100vh;
  }

  .login-page {
    background-image: url({{URL::asset('/images/login-bg.jpg')}}); 
  }
  .card-border {
    border-top:  3px solid #2b88c4;
  }
  .btn-block {
    background-color: #2b88c4;
    color: #fff;
  }
</style>
</head>
<body class="login-page">
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-border">
      <div class="card-header text-center">
        <a href="#" class="h1">
          <img class="img-fluid"  width="100" src="{{asset('/images/ypay.png')}}" alt="">
        </a>
      </div>
      <div class="card-body">
        <h4 class="text-center">Admin Panel</h4>
        <p class="login-box-msg pb-0">Sign in to start your session</p>
        @if(session()->has('error'))
          <div class="alert alert-danger text-center" role="alert">
            {{session()->get('error')}}
          </div>
        @endif
        <form method="POST" action="{{ route('login') }}" class="py-4 needs-validation" {{ $errors->has('email') ? 'was-validated' : ''  }}"" novalidate>
          @csrf
          <div class="input-group mb-3">
            <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : ((!$errors->has('email') && old('email')) ? 'is-valid' : '') }}" placeholder="Email"  name="email" value="{{ old('email') }}">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
            @if ($errors->has('email'))
              <div class="invalid-feedback d-block">
                {{ $errors->first('email') }}
              </div>
            @endif
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control password-field {{ $errors->has('password') ? 'is-invalid' : ((!$errors->has('password') && old('password')) ? 'is-valid' : '') }}" placeholder="Password" autocapitalize="off" name="password"  value="{{ old('password') }}" >
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock toggle-password"></span>
              </div>
            </div>
            @if ($errors->has('password'))
              <div class="invalid-feedback d-block">
                {{ $errors->first('password') }}
              </div>
            @endif
          </div>
          <div class="row">
            <div class="col-12">
              {{-- <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember" class="text-muted" style="font-weight: 400;">
                  Keep me logged in
                </label>
              </div> --}}
            </div>
            <!-- /.col -->
            <div class="col-12 mt-3">
              <button type="submit" class="btn btn-block">Submit</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="{{asset('/js/adminlte.min.js')}}"></script>
  <script>
        $(".toggle-password").click(function() {
          $(this).toggleClass("fas fa-lock fas fa-unlock");
          // var input = $($(this).attr("toggle"));
          var input = $('.password-field');
          // console.log('inputinput', input)
          if (input.attr("type") == "password") {
            input.attr("type", "text");
          } else {
            input.attr("type", "password");
          }
      });
  </script>
</body>
</html>