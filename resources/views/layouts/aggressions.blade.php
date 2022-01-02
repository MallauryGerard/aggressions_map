<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="Carte interactive des agressions">
    <meta name="keywords" content="carte, map, agressions, ville">

    <title>Carte interactive des agressions</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
    <!-- Minimum scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <!-- Fonts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/js/all.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- MDB -->
    <link href="/public/css/mdb.min.css" rel="stylesheet">
    <link href="/public/css/leaflet/filter.css" rel="stylesheet">
    <script type="text/javascript" src="/public/js/leaflet/filter.js"></script>
    <script type="text/javascript" src="/public/js/leaflet/leaflet-easy-button.js"></script>
</head>
<style>
    html, body { height: 100%; margin: 0; padding: 0; border: 3px solid #e0dfdf;}
</style>

<body>
    @yield('content')

    <script  type="text/javascript" src="/public/js/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/public/js/mdb.min.js"></script>
</body>

</html>
