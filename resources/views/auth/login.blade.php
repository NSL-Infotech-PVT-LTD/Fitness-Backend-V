@extends('layouts.app')

@section('content')
<div class="main_top">
<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-md-6 col-sm-6">
     <div class="left_img">
     
    </div>
    </div>
        <div class="col-md-6 col-sm-6 right_text">
         
                <div class=" text-center login_text">
                <h2>Welcome To</h2>


                <img src="{{ asset('logo_black.png') }} ">
                    <p>To Keep connect With us Please <br>
log in Your Info</p>
                </div>
                @if($errors->any())
                <div class="alert alert-danger text-center">
                    {{$errors->first()}}
                </div>
                @endif

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                          

                            <div class="col-md-12">
                                <input id="email" type="email" placeholder="Your Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                           

                            <div class="col-md-12">
                                <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button><br>

                                @if (Route::has('password.request'))
                                    
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
   
</div>
</div>
@endsection
