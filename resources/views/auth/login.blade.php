@extends('layouts.app')
@section('content')
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper auth p-0 theme-two">
            <div class="row d-flex align-items-stretch">
                <div class="col-md-4 banner-section d-none d-md-flex align-items-stretch justify-content-center">
                    <div class="bg-1">
                        <img src="{{asset('public/images/left-banner.jpg')}}" height="100%" width="100%">
                    </div>
                </div>
                <div class="col-12 col-md-8 h-100 bg-white">
                    <div class="auto-form-wrapper d-flex align-items-center justify-content-center flex-column">
                        <div class="nav-get-started">
                           <!-- <img src="" height="40px" width="170px">-->
                        </div>
                        <form method="POST" action="{{ route('login') }}">
                            {{csrf_field()}}
                            <h3 class="mr-auto">¡Hola! Bienvenido!</h3>
                            <p class="mb-5 mr-auto">Ingresa tus datos.</p>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                          <i class="mdi mdi-account-outline"></i>
                                        </span>
                                    </div>
                                    <input id="email" type="email"
                                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           name="email" value="{{ old('email') }}" required autofocus placeholder="Email">

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                          <i class="mdi mdi-lock-outline"></i>
                                        </span>
                                    </div>
                                    <input type="password" name="password" class="form-control" placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary submit-btn">INGRESAR</button>
                            </div>
                            <div class="wrapper mt-5 text-gray">
                                <p class="footer-text">Copyright © 2018 Gestion Avisos 2.0. All rights reserved.</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
@endsection
