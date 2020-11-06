@extends('layouts.master')

@section('content')
<div class="container">
    <div class="auth-pages">
        <div class="auth-left">
            <h2>Returning Customer</h2>
            <div class="spacer"></div>
            <form action="{{ route('login') }}" method="POST">
                {{ csrf_field() }}

                <input type="email" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                <input type="password" id="password" name="password" placeholder="Password" required>

                <div class="login-container">
                    <button type="submit" class="auth-button">Login</button>
                    <label>
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>  {{ __('Remember Me') }}
                    </label>
                </div>
                <div class="spacer"></div>
                <a href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            </form>
        </div>
        <div class="auth-right">
            <h2>New Customer</h2>
            <div class="spacer"></div>
            <p><strong>Save time now.</strong></p>
            <p>You don't need account to checkout.</p>
            <div class="spacer"></div>
            <a href="{{ route('guestCheckout.index') }}" class="button">Continue as Guest</a>
            <div class="spacer"></div>
            &nbsp;
            <div class="spacer"></div>
            <p><strong>Save time later.</strong></p>
            <p>Create an account for fast checkout and easy access to order history.</p>
            <div class="spacer"></div>
            <a href="{{ route('register') }}" class="button">Create Account</a>
        </div>
    </div>
</div>
@endsection
