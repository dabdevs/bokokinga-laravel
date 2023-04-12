@extends('front/master')

<style>
    form {
        max-width: 350px;
        margin: 0 auto;
    }
</style>

@section('content')
    <div class="container py-5">
        <form class="my-5">
            <h1>Login</h1> <br>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password">
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="remember_me">
                <label class="form-check-label" for="remember_me">Remember me</label>
            </div>
            <button type="submit" class="btn btn-custom-primary">Login</button>
        </form>
    </div>
@endsection
