@extends('layouts.aggressions')

@section('content')
    <div class="dropdown d-none d-sm-block">
        <button class="btn btn-sm btn-primary dropdown-toggle" style="position: absolute; top: 10px; right: 10px; z-index: 999; width: 100px;" type="button" id="menu" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">Menu</button>
        <div class="dropdown-menu dropdown-primary">
            <a class="dropdown-item" href="{{ route('exportCSV') }}">Exporter les points</a>
            <a class="dropdown-item" href="{{ route('admin.showLogin') }}">Accès modérateur</a>
        </div>
    </div>
    <div id="map" style="width:100%; height:100%"></div>

    <button id="report" style="position: absolute; top: 10px; left: 58px; z-index: 999; width: 260px;" type="button" class="btn btn-danger font-weight-bold" onclick="report()">Signaler une agression !</button>

    <div class="modal fade" id="reportAggression" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-notify modal-danger" role="document">
            <!--Content-->
            <div class="modal-content">
                <!--Header-->
                <div class="modal-header text-center">
                    <h4 class="modal-title white-text w-100 font-weight-bold py-2">Informations sur l'agression</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>

                <!--Body-->
                <div class="modal-body">
                    <form action="" method="post" id="storeAggression">
                        <input id="coordinates" type="hidden" name="coordinates" value="">

                        <div class="md-form mb-3">
                            <label for="date">Date de l'agression</label><br>
                            <input type="date" class="form-control" id="date" name="date" value="{{ date("Y-m-d") }}"
                                   max="{{ date("Y-m-d") }}" min="2021-01-01" required>
                        </div>

                        <div class="md-form mb-3">
                            <label for="time">Heure approximative</label><br>
                            <input type="time" id="time" class="form-control" name="time"
                                   value="{{ date("H") . ':00' }}"
                                   required>
                        </div>

                        <div class="md-form mb-3">
                        <textarea id="description" class="form-control md-textarea" name="description" rows="2"
                                  required></textarea>
                            <label for="description">Description des faits</label>
                            <small>La description sera publique</small>
                        </div>

                        <div class="md-form mb-3">
                            <label for="contact">Moyen de contact (facultatif et confidentiel)</label>
                            <input type="text" id="contact" class="form-control" name="contact">
                        </div>
                        <br>
                        <input type="submit" class="btn btn-outline-primary waves-effect" value="Envoyer">
                    </form>
                </div>
                <!--/.Content-->
            </div>
        </div>
    </div>

    <div id="bottom_advice"
         style="position: fixed; bottom: 0; left: 0; right: 0; width: 100%; height: 60px; background-color: #ff3547; z-index: 9999; display: none;">
        <p style="line-height: 60px; text-align: center; color: white; font-weight: bold;">Cliquez sur la carte où a eu
            lieu
            l'agression (approximativement)</p>
    </div>

    <div class="modal fade right" id="plainte" tabindex="-1" role="dialog" aria-labelledby="plainte"
         aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog modal-side modal-bottom-right" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h4 class="modal-title w-100 font-weight-bold">Pensez à porter plainte !</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-justify">
                        Cette carte n'est pas liée aux services de la police.
                        Pour augmenter les chances que les agresseurs soient retrouvés et empêcher que cela se reproduise,
                        il est important de porter plainte à la police.
                        Vous pouvez le faire via un simple formulaire en ligne, en cliquant sur ce bouton. <br>
                        <a class="btn btn-sm btn-rounded btn-primary" href="https://www.police.be/fr/declaration-en-ligne" target="_blank">Je porte plainte !</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            initialize();
        });

        function report() {
            if ($('#report').text() == 'Signaler une agression !') {
                $('.leaflet-container').css('cursor', 'url(images/aggressions/redpoint.png), auto');
                $('#report').html('Annuler...');
                $('#bottom_advice').css('display', 'block');
                $('.leaflet-marker-icon, .leaflet-marker-shadow').css('display', 'none');
                $('.legend').css('display', 'none');
                $('body').css('border', '3px solid #ff3547');
            } else {
                $('#report').text('Signaler une agression !');
                $('.leaflet-container').css('cursor', 'default');
                $('#bottom_advice').css('display', 'none');
                $('.leaflet-marker-icon, .leaflet-marker-shadow').css('display', 'block');
                $('.legend').css('display', 'block');
                $('body').css('border', '3px solid #e0dfdf');
            }
        }

        var map = L.map('map').setView([50.465, 4.863], 16);

        $("#storeAggression").submit(function (e) {
            e.preventDefault(); // avoid to execute the actual submit of the form.
            var form = $(this);
            var url = "{{ route('store') }}";
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(), // serializes the form's elements.
                success: function (data) {
                    if (data) {
                        coordinates = (data.result).replace('LatLng(', '');
                        coordinates = coordinates.replace(')', '');
                        lat = coordinates.split(',')[0];
                        long = coordinates.split(',')[1];
                        L.marker([lat, long]).addTo(map); // Add my aggression marker
                        $('#reportAggression').modal('hide');
                        toastr.success('Le point est soumis à validation (il sera bientôt ajouté). <br>Merci et courage !');
                        $('#plainte').modal('show');
                    }
                }
            });
        });

        function initialize() {
            var exhibIcon = new L.Icon({
                iconUrl: 'images/aggressions/marker-icon-exhib-2x.png',
                shadowUrl: 'images/aggressions/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            var degradationIcon = new L.Icon({
                iconUrl: 'images/aggressions/marker-icon-degradation-2x.png',
                shadowUrl: 'images/aggressions/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            var stealcarIcon = new L.Icon({
                iconUrl: 'images/aggressions/marker-icon-steal-car-2x.png',
                shadowUrl: 'images/aggressions/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            var stealIcon = new L.Icon({
                iconUrl: 'images/aggressions/marker-icon-steal-2x.png',
                shadowUrl: 'images/aggressions/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            var aggressionIcon = new L.Icon({
                iconUrl: 'images/aggressions/marker-icon-aggression-2x.png',
                shadowUrl: 'images/aggressions/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });


            @foreach ($aggressions as $aggression)
                @switch($aggression->type)
                    @case('Exhibition')
                    icon = exhibIcon;
                @break
                    @case('Dégradation')
                    icon = degradationIcon;
                @break
                    @case('Vol avec effraction')
                    icon = stealcarIcon;
                @break
                    @case('Vol (simple ou aggravé)')
                    icon = stealIcon;
                @break
                    @case('Agression (physique ou verbale)')
                    icon = aggressionIcon;
                @break
                    @default
                    icon = aggressionIcon;
                @endswitch
                (L.marker([{{ $aggression->lat }}, {{ $aggression->long }}], {icon: icon, tags: {!! $aggression->tags !!} }).addTo(map)).
                bindPopup("<b>{{ $aggression->getFormatDate('datetime') }}</b><br>{{ $aggression->description }}");
            @endforeach

            var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19
                });

            map.addLayer(osmLayer);
            map.on('click', onMapClick);

            var legend = L.control({position: 'bottomleft'});
            legend.onAdd = function (map) {
                var div = L.DomUtil.create('div', 'info legend py-2 px-3 bg-white z-depth-2 rounded-lg');
                div.innerHTML += "<h5>Légende</h5>";
                div.innerHTML += '<span style="line-height: 25px;"><i style="background: #f94e51; display: block; float: left; height: 20px; width: 20px; margin-right: 10px;"></i><span>Agression (physique ou verbale)</span></span><br>';
                div.innerHTML += '<span style="line-height: 25px;"><i style="background: #ee702b; display: block; float: left; height: 20px; width: 20px; margin-right: 10px;"></i><span>Vol (simple ou aggravé)</span></span><br>';
                div.innerHTML += '<span style="line-height: 25px;"><i style="background: #ebb94b; display: block; float: left; height: 20px; width: 20px; margin-right: 10px;"></i><span>Vol avec effraction</span></span><br>';
                div.innerHTML += '<span style="line-height: 25px;"><i style="background: #3f9f82; display: block; float: left; height: 20px; width: 20px; margin-right: 10px;"></i><span>Dégradation</span></span><br>';
                div.innerHTML += '<span style="line-height: 25px;"><i style="background: #536f89; display: block; float: left; height: 20px; width: 20px; margin-right: 10px;"></i><span>Exhibition</span></span><br>';
                return div;
            };
            legend.addTo(map);

            L.control.tagFilterButton({
                data: ["Aujourd'hui", "7 derniers jours", "30 derniers jours", "Tout"],
                icon: '<img src="https://maydemirx.github.io/leaflet-tag-filter-button/filter.png">',
                filterOnEveryClick: true
            }).addTo(map);

            $('#reportAggression').on('hidden.bs.modal', function () {
                $('#report').html('Signaler une agression !');
                $('.leaflet-container').css('cursor', 'default');
                $('#bottom_advice').css('display', 'none');
                $('.leaflet-marker-icon, .leaflet-marker-shadow').css('display', 'block');
                $('.legend').css('display', 'block');
                $('body').css('border', '3px solid #e0dfdf');
            })
        }

        function onMapClick(e) {
            if ($('#report').text() == 'Annuler...') {
                $('#bottom_advice').css('display', 'none');
                $('.leaflet-marker-icon, .leaflet-marker-shadow').css('display', 'block');
                $('.legend').css('display', 'block');
                $('#reportAggression').modal('show');
                $('#report').html('Remplissez le formulaire');
                $('#coordinates').val(e.latlng);
            }
        }
    </script>

@endsection
