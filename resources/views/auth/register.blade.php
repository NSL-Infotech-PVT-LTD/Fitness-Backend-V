@extends('layouts.app')

@section('content')

<div class="main_top">
<div class="container">
    <div class="row justify-content-center">
    
    <div class="col-md-6 col-sm-6">
     <div class="left_img res">
     
    </div>
    </div> 
    <div class="col-md-6 col-sm-6 right_text">
             
          
 <div class=" text-center login_text">
                <h2>Welcome To</h2>


                <img src="/volt/public/logo_black.png">
                    <p>      Your free account is 
only 2 seconds away!</p>
                </div>


                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
 
                            <div class="col-md-12 col-sm-12">
                                <input id="name" type="text" placeholder="Name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
 
                          <div class="col-md-12 col-sm-12">
                                <input id="email" type="email" placeholder="Your Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                          

                            <div class="col-md-12 col-sm-12">
                                <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                          

                            <div class="col-md-12 col-sm-12">
                                <input id="password-confirm" type="password" placeholder="Confirm Password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-12 co-sm-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create your free account') }}<i class="fa fa-long-arrow-right" aria-hidden="true"></i>

                                </button>
                            </div>
                        </div>
                        <p class="bottom_text"> We take your data privacy very seriously and will not share your details with anyone else. By registering you accept our Terms & Conditions and Privacy Policy.</p>
                    </form>
                </div>
            
        </div>
    </div>
</div>
</div>
@endsection
