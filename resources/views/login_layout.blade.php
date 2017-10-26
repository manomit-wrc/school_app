@extends('welcome')
@section('content')
<!-- begin login -->
        <div class="login login-v2" data-pageload-addclass="animated fadeIn">
            <!-- begin brand -->
            <div class="login-header">
                <div class="brand">
                    Learning Management System
                    
                </div>
            </div>

            
            <!-- end brand -->
            <div class="login-content">
                @if(Session::has('login-failed'))
                    <p class="login-box-msg" style="color: white;">{{ Session::get('login-failed') }}</p>
                @endif

                <form action="/login-submit" method="POST" class="margin-bottom-0">
                    {{ csrf_field() }}

                    <div class="form-group m-b-20 {{ $errors->has('user_email') ? 'has-error' : '' }}">
                        <input type="text" class="form-control input-lg" placeholder="Email Address" name="user_email" />
                        <span class="input-group col-md-offset-2" style="color: white;">{{ $errors->first('user_email') }}</span>
                    </div>
                    <div class="form-group m-b-20 {{ $errors->has('password') ? 'has-error' : '' }}">
                        <input type="password" class="form-control input-lg" placeholder="Password" name="password" />
                        <span class="input-group col-md-offset-2" style="color: white;">{{ $errors->first('password') }}</span>
                    </div>
                    <div class="login-buttons">
                        <button type="submit" class="btn btn-success btn-block btn-lg">Sign me in</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- end login -->

@endsection