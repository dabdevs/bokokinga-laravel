<style>
    #sidebar-menu {
        display: none;
    }
</style>

@extends('dashboard/layout')

@section('content')
    <div class="container py-5">
        <form class="my-5 mx-auto" method="POST" action="{{ route('admin.authenticate') }}" style="max-width: 350px">
            {{ view('front/messages') }}
            @csrf
            <h1>Admin</h1>
            <br>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" id="email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password">
                <button type="submit" class="btn btn-custom-primary">Login</button>
            </div>
        </form>
    </div>
@endsection
