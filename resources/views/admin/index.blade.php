@extends('layouts.aggressions')

@section('content')
    <style>
        .waves-input-wrapper {
            display: block !important;
        }
    </style>

    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" style="position: absolute; top: 10px; right: 10px; z-index: 999; width: 200px;" type="button" id="menu" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">Déconnexion</button>
        <div class="dropdown-menu dropdown-primary">
            <a class="dropdown-item" href="{{ route('admin.logout') }}">Déconnexion</a>
        </div>
    </div>

    @if ($aggressions->isEmpty())
        <h1>Aucune nouvelle agression à valider...</h1>
    @else
        @foreach($aggressions as $aggression)
            <form action="{{ route('admin.moderate') }}" style="display: none;">
                <input type="hidden" name="id" value="{{ $aggression->id }}">
                <input type="hidden" name="author_ip" value="{{ $aggression->ip }}">
                <div class="row">
                    <div class="col-md-12 md-form">
                        <p class="text-center" style="width: 70%; font-size: 1.1rem; font-weight: bold; margin: 0 auto; margin-top: 20px;">
                            <b>{{ $aggression->description }}</b>
                        </p>
                        @if(isset($count_ips[$aggression->ip]) && $count_ips[$aggression->ip] > 1)
                            <p class="text-center" style="width: 70%; font-size: 1.2rem; font-weight: bold; margin: 0 auto; margin-top: 20px;">
                                <u>Cet auteur en a déjà publié : <b>{{ $count_ips[$aggression->ip] - 1 }}</b></u>
                            </p>
                        @endif
                    </div>
                </div>
                <div class="row" style="position: fixed; bottom: 0px; width: 100%; margin: 0 auto;">
                    <div class="col-sm-6 my-1">
                        <input type="submit" class="btn btn-lg btn-block btn-primary" value="Agression (physique ou verbale)">
                    </div>
                    <div class="col-sm-6 my-1">
                        <input type="submit" class="btn btn-lg btn-block btn-secondary" value="Vol (simple ou aggravé)">
                    </div>
                    <div class="col-sm-6 my-1">
                        <input type="submit" class="btn btn-lg btn-block btn-warning" value="Vol avec effraction">
                    </div>
                    <div class="col-sm-6 my-1">
                        <input type="submit" class="btn btn-lg btn-block btn-info" value="Exhibition">
                    </div>
                    <div class="col-sm-6 my-1">
                        <input type="submit" class="btn btn-lg btn-block btn-default" value="Dégradation">
                    </div>
                    <div class="col-sm-6 my-1">
                        <button onclick="skip()" type="button" class="btn btn-lg btn-block btn-light">Passer...</button>
                    </div>
                    <div class="col-sm-6 my-1">
                        <input type="submit" class="btn btn-lg btn-block btn-danger" value="Refuser">
                    </div>
                    <div class="col-sm-6 my-1">
                        <input type="submit" class="btn btn-lg btn-block btn-dark" value="Bloquer">
                    </div>
                </div>
            </form>
        @endforeach
    @endif

    <script>
        function skip() {
            $("form:first").remove();
            $("form:first").show();
        }

        $("form:first").show();

        $("form").submit(function (e) {
            // avoid to execute the actual submit of the form.
            e.preventDefault();
            var form = $(this);
            var url = "{{ route('admin.moderate') }}";
            $.ajax({
                type: "PUT",
                url: url,
                data: form.serialize() + '&type=' + e.originalEvent.submitter.value, // serializes the form's elements.
                success: function (data) {
                    if (data.success !== 1) {
                        alert('Bug');
                        console.log(data);
                    } else {
                        $("form:first").remove();
                        $("form:first").show();
                    }
                }
            });
        });
    </script>

@endsection
