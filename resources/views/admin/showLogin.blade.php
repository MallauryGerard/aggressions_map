@extends('layouts.aggressions')

@section('content')
    <div class="card" >
        <h5 class="card-header primary-color white-text text-center py-4">
            <strong>Connexion</strong>
        </h5>
        <div class="card-body px-lg-5 pt-0">
            <form class="text-center" style="color: #757575;" method="POST" action="{{ route('admin.login') }}">
                <!-- username -->
                <div class="md-form">
                    <input type="text" id="admin_username" name="admin_username" class="form-control">
                    <label for="admin_username">Pseudo</label>
                </div>

                <!-- Password -->
                <div class="md-form">
                    <input type="password" id="admin_password" name="admin_password" class="form-control">
                    <label for="admin_password">Mot de passe</label>
                </div>

                <!-- Sign in button -->
                <button class="btn btn-outline-primary btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">
                    Connexion
                </button>
            </form>
        </div>
    </div>
@endsection
