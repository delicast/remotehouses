<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Theme Made By www.w3schools.com - No Copyright -->
    <title>HouseHolds Digitization</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('/bootstrap-3.3.6/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
    <link href="{{ asset('/openlayers/v3.14.2/ol.css') }}" rel="stylesheet">
    <script src="{{ asset('/jquery/jquery-1.12.1.min.js') }}"></script>
    <script src="{{ asset('/bootstrap-3.3.6/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/openlayers/v3.14.2/ol-debug.js') }}"></script>

    <link rel="stylesheet" href={{ asset('/css/households.css') }}>

</head>
<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">



@yield('content')

<footer class="container-fluid text-center">
    <a href="#myPage" title="To Top">
        <span class="glyphicon glyphicon-chevron-up"></span>
    </a>
    <p>TEST VERSION - NOT FULLY FUNCTIONAL </p>
    <p>(c) SASSCAL 2017 </p>
</footer>



</body>
</html>
