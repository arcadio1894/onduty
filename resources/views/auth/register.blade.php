@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col s6 offset-s3">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Register</span>
                    <form class="s12" role="form" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">person_pin</i>
                                <input id="name" name="name" type="text" class="validate" required autofocus>
                                <label for="name" data-error="Please write your name" data-success="right" class="" >Name</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">email</i>
                                <input id="email" name="email" type="email" class="validate">
                                <label for="email" data-error="Please write your email" data-success="right" class="">Email</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">lock</i>
                                <input id="password" type="password" class="validate" name="password" required>
                                <label for="password" data-error="Please write your password" data-success="right" class="">Password</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">lock</i>
                                <input id="password_confirmation" type="password" class="validate" required name="password_confirmation">
                                <label for="password_confirmation" data-error="Please write your password" data-success="right" class="">Confirm Password</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <button type="submit" class="waves-effect waves-light btn">Register</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
